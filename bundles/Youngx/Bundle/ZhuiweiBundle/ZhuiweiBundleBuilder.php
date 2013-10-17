<?php

namespace Youngx\Bundle\ZhuiweiBundle;

use Youngx\DI\ContainerBuilder;
use Youngx\DI\DefinitionCollection;

class ZhuiweiBundleBuilder extends ContainerBuilder
{
    public function collect(DefinitionCollection $collection)
    {
        $collection->register('front.listener.collect', __NAMESPACE__ . '\Listener\CollectListener')
            ->tag('listener');
    }
}