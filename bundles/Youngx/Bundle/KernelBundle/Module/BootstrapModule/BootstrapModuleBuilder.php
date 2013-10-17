<?php

namespace Youngx\Bundle\KernelBundle\Module\BootstrapModule;

use Youngx\DI\ContainerBuilder;
use Youngx\DI\DefinitionCollection;

class BootstrapModuleBuilder extends ContainerBuilder
{
    public function collect(DefinitionCollection $collection)
    {
        $collection->register('bootstrap.listener.main', __NAMESPACE__ . '\Listener\MainListener')
            ->subscribe('context')
            ->tag('listener');
    }
}