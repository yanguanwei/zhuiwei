<?php

namespace Youngx\Bundle\KernelBundle\Listener;

use Youngx\MVC\Menu\Menu;
use Youngx\EventHandler\Event\GetSortableArrayEvent;
use Youngx\EventHandler\Registration;
use Youngx\MVC\Context;

class BlockListener implements Registration
{
    /**
     * @var Context
     */
    protected $context;

    public function setContext(Context $context)
    {
        $this->context = $context;
    }

    public function renderMessages(GetSortableArrayEvent $event)
    {
        foreach ($this->context->flash()->all() as $type => $messages) {
            foreach ($messages as $i => $message) {
                $event->set("{$type}.{$i}", $this->context->html('message', array(
                            '#type' => $type,
                            '#content' => $message
                        )), -100);
            }
        }
    }

    public function debug(GetSortableArrayEvent $event)
    {
        if ($this->context->app()->isDebug()) {
            $queries = $this->context->db()->getQueries();
            if ($queries) {
                $s = '<pre>'.implode("\n", $queries).'</pre>';
                $event->set('queries', $s, 100);
            }
            $endTime = microtime(true);
            $event->set('runtime', sprintf('<pre>Runtime: %s</pre>', (($endTime - $this->context->app()->getStartTime()) * 1000) . 'ms'), 101);
        }
    }

    public function breadcrumbs(GetSortableArrayEvent $event)
    {
        $breadcrumbs = array();
        $menu = $this->context->request()->getMenu();
        if ($menu) {
            $name = $this->context->request()->getRouteName();
            $router = $this->context->router();
            while ($name) {
                $menu = $router->getMenu($name);
                array_unshift($breadcrumbs, array(
                        'label' => $menu->getLabel(),
                        'url' => $this->context->generateUrlWithCurrent($name)
                    ));
                $name = $menu->getParent();
            }
        }

        $event->set('breadcrumbs', $this->context->widget('Breadcrumbs', array(
                    '#breadcrumbs' => $breadcrumbs
                )));
    }

    public static function registerListeners()
    {
        return array(
            'kernel.block#content' => 'renderMessages',
            'kernel.block#breadcrumbs' => 'breadcrumbs',
            'kernel.block#body' => array('debug', 1024)
        );
    }
}