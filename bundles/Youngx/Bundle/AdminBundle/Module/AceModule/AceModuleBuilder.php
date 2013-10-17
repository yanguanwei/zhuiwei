<?php

namespace Youngx\Bundle\AdminBundle\Module\AceModule;

use Youngx\DI\ContainerBuilder;
use Youngx\DI\DefinitionCollection;

class AceModuleBuilder extends ContainerBuilder
{
    public function collect(DefinitionCollection $collection)
    {
        $collection->register('ace.listener.main', __NAMESPACE__ . '\Listener\MainListener')
            ->subscribe('context')
            ->tag('listener');
    }
}