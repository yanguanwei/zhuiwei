<?php

namespace Youngx\Bundle\UserBundle\Module\ActivationModule;


use Youngx\Kernel\Container\ContainerBuilder;
use Youngx\Kernel\Container\DefinitionCollection;

class ActivationModuleBuilder extends ContainerBuilder
{
    public function collect(DefinitionCollection $collection)
    {
        $collection->register('user.activation.listener.collect', __NAMESPACE__ . '\Listener\CollectListener')
            ->tag('listener');
    }
}