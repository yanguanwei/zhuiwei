<?php

namespace Youngx\Bundle\KernelBundle\Listener;

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Youngx\EventHandler\Event\GetValueEvent;
use Youngx\EventHandler\Handler;
use Youngx\EventHandler\Registration;
use Youngx\MVC\Context;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\Exception\HttpException;
use Youngx\MVC\Exception\MethodNotAllowedHttpException;
use Youngx\MVC\Exception\NotFoundHttpException;
use Youngx\MVC\Menu\Menu;
use Youngx\MVC\Menu\MenuCollection;
use Youngx\MVC\Request;
use Youngx\MVC\Router;

class RoutingListener implements Registration
{
    /**
     * @var Context
     */
    protected $context;

    public function setContext(Context $context)
    {
        $this->context = $context;
    }

    public function routing(GetResponseEvent $event, Request $request)
    {
        $router = $this->context->router();
        $router->setRequest($request);
        $handler = $this->context->handler();
        try {
            $attributes = $router->match($request->getPathInfo() === '/' ? $request->getPathInfo() : rtrim($request->getPathInfo(), '/'));
            $routeName = $attributes['_route'];
            $routeParams = $attributes;
            unset($routeParams['_route']);
            $attributes['_route_params'] = $routeParams;

            $menu = $router->getMenu($routeName);

            $attributes['_controller'] = $menu->getController();

            $route = $router->getRoute($routeName);
            foreach ($menu->getLoaders() as $key => $loader) {
                if (isset($attributes[$key])) {
                    $value = $handler->triggerForValue(array("kernel.menu.loader#{$loader}", 'kernel.menu.loader'),  $attributes[$key], $loader, $key);
                    if (null !== $value || $route->hasDefault($key)) {
                        $attributes[$key] = $value;
                    } else {
                        throw new ResourceNotFoundException();
                    }
                }
            }

            $request->attributes->add($attributes);
            $request->setRoute($route);
            $request->setRouteName($routeName);
            $request->setMenu($menu);

            $menuGroups[] = $current = $menu->getGroup();
            while ($parent = $router->getMenuGroupParent($current)) {
                $menuGroups[] = $current = $parent;
            }
            $request->setMenuGroups($menuGroups);

            $access = $menu->getAccess();

            if ($access === true) {
                return ;
            }

            if ($access === false) {
                $this->throwHttpException($event);
            }

            $events = array();
            if ($access) { $events[] = "kernel.access#{$access}";}
            $events[] = 'kernel.access';
            if (true !== $handler->triggerWithMenu($events, $event, $request, $access)) {
                $events = array();
                if ($access) { $events[] = "kernel.access.deny#{$access}";}
                $events[] = 'kernel.access.deny';
                $handler->triggerWithMenu($events, $event, $request, $access);
                $this->throwHttpException($event);
            }
        } catch (ResourceNotFoundException $e) {
            $message = sprintf('No route found for "%s %s"', $request->getMethod(), $request->getPathInfo());
            throw new NotFoundHttpException($message, $e);
        } catch (MethodNotAllowedException $e) {
            $message = sprintf('No route found for "%s %s": Method Not Allowed (Allow: %s)',
                $request->getMethod(), $request->getPathInfo(), strtoupper(implode(', ', $e->getAllowedMethods()))
            );
            throw new MethodNotAllowedHttpException($e->getAllowedMethods(), $message, $e);
        }
    }

    protected function throwHttpException(GetResponseEvent $event)
    {
        if (!$event->hasResponse()) {
            if ($this->context->identity()->isLogged()) {
                throw new HttpException(403, 'Access Denied.');
            } else {
                throw new HttpException(401, 'Not Authenticated.');
            }
        }
    }

    public function load($id, $entityType)
    {
        if ($this->context->schema()->hasEntityType($entityType)) {
            return $this->context->repository()->load($entityType, $id);
        }
    }

    public static function registerListeners()
    {
        return array(
            'kernel.routing' => 'routing',
            'kernel.menu.loader' => 'load'
        );
    }
}