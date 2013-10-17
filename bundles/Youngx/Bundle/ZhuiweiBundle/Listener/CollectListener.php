<?php

namespace Youngx\Bundle\ZhuiweiBundle\Listener;

use Youngx\EventHandler\Registration;
use Youngx\MVC\Menu\MenuCollection;

class CollectListener implements Registration
{
    public function collectMenu(MenuCollection $collection)
    {
        $zhuiwei = $collection->getCollection('zhuiwei');
        $zhuiwei->add('home', '/', '首页', 'Home@Zhuiwei')
            ->setAccess(true);
    }

    public static function registerListeners()
    {
        return array(
            'kernel.menu.collect' => 'collectMenu'
        );
    }
}