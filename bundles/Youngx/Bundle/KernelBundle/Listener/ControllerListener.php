<?php

namespace Youngx\Bundle\KernelBundle\Listener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Youngx\DI\Container;
use Youngx\EventHandler\Registration;
use Youngx\MVC\Application;
use Youngx\MVC\Context;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\Request;

class ControllerListener implements Registration
{
    /**
     * @var Application
     */
    private $app;
    /**
     * @var Container
     */
    private $container;

    /**
     * @var Context
     */
    private $context;

    private $defaultControllerClass;
    private $defaultControllerMethod;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger, $defaultControllerMethod = 'index', $defaultControllerClass = 'Index')
    {
        $this->defaultControllerMethod = $defaultControllerMethod;
        $this->defaultControllerClass = $defaultControllerClass;

        $this->logger = $logger;
    }

    public function setApp(Application $app)
    {
        $this->app = $app;
    }

    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    public function setContext(Context $context)
    {
        $this->context = $context;
    }

    public function controller(GetResponseEvent $event, Request $request)
    {
        $menu = $request->getMenu();
        if ($menu) {
            $callback = $this->resolveController($menu->getController(), $request);
            $arguments = $this->container->arguments($callback, $request->attributes->all());
            $response = call_user_func_array($callback, $arguments);

            if ($response instanceof Response) {
                $event->setResponse($response);
            }
        }
    }

    protected function resolveController($controller, Request $request)
    {
        if (is_array($controller) || (is_object($controller) && method_exists($controller, '__invoke'))) {
            return $controller;
        }

        if (false === strpos($controller, '.') && false === strpos($controller, '@')) {
            if (method_exists($controller, '__invoke')) {
                return new $controller;
            } elseif (function_exists($controller)) {
                return $controller;
            }
        }

        $callable = $this->createController($controller, $request);

        if (!is_callable($callable)) {
            throw new \InvalidArgumentException(sprintf('The controller for URI "%s" is not callable.', $request->getPathInfo()));
        }

        return $callable;
    }

    /**
     *
     * @param string $controller ::method@Bundle | @Bundle |
     *                           Controller@Bundle |
     *                           PathTo.Controller::method@Bundle
     * @param Request $request
     * @throws \InvalidArgumentException
     * @return callback
     */
    protected function createController($controller, Request $request)
    {
        if (preg_match('/^([a-zA-Z0-9\.]+)?(::([a-zA-Z0-9]+))?@([a-zA-Z0-9]+)(:([a-zA-Z0-9]+))?$/', $controller, $match)) {
            $controller = $match[1];
            $method = $match[3];
            $bundle = $match[4];
            $module = isset($match[6]) ? $match[6] : null;

            if (!$method) {
                $method =  $this->defaultControllerMethod;
            }

            $method .= 'Action';
            $controllerClass = $this->app->generateClass($bundle, $controller ? $controller : $this->defaultControllerClass, 'Controller', $module);

            $this->logger->debug('dispatch: controller', array(
                    'class' => $controllerClass,
                    'bundle' => $bundle,
                    'module' => $module,
                    'method' => $method,
                ));

            $request->setBundle($this->app->getBundle($bundle));
            $request->setModule($module);

            $controller = $this->container->instantiate($controllerClass, $request->attributes->all());

            return array($controller, $method);
        } else {
            throw new \InvalidArgumentException(sprintf('Invalid Controller Format: [%s]', $controller));
        }
    }

    public static function registerListeners()
    {
        return array(
            'kernel.controller' => 'controller'
        );
    }
}