<?php

define('Y_TIME', time());

class App extends \Youngx\MVC\Application
{
    protected function registerBundles()
    {
        return array(
            new Youngx\Bundle\KernelBundle\KernelBundle(),
            new Youngx\Bundle\jQueryBundle\jQueryBundle(),
            new Youngx\Bundle\CategoryBundle\CategoryBundle(),
            new Youngx\Bundle\UserBundle\UserBundle(),
            new Youngx\Bundle\AdminBundle\AdminBundle(),
            new Youngx\Bundle\ZhuiweiBundle\ZhuiweiBundle(),
            new Youngx\Bundle\ArchiveBundle\ArchiveBundle(),
        );
    }

    protected function registerLocations(\Youngx\MVC\Locator $locator)
    {
        $locator->register('app', __DIR__)
            ->register('cache', "app://cache/{$this->environment}")
            ->register('web', dirname(__DIR__) . '/web')
            ->register('public', 'web://public', '/public')
            ->register('assets', 'web://assets', '/assets');
    }

    protected function registerConfiguration()
    {
        return include __DIR__ . '/config/main.php';
    }
}
