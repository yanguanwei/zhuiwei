<?php

namespace Youngx\Bundle\ZhuiweiBundle\Listener;

use Youngx\Bundle\CategoryBundle\Entity\CategoryEntity;
use Youngx\EventHandler\Registration;
use Youngx\MVC\Assets;
use Youngx\MVC\Context;
use Youngx\MVC\RenderableResponse;

class MainListener implements Registration
{
    /**
     * @var Context
     */
    protected $context;

    public function setContext(Context $context)
    {
        $this->context = $context;
    }

    public function registerCoreAssets(Assets $assets)
    {
        $assets->registerStyleUrl('global', 'Zhuiwei/css/global.css');
        $assets->registerStyleUrl('top', 'Zhuiwei/css/top.css');
        $assets->registerStyleUrl('foot', 'Zhuiwei/css/foot.css');
    }

    public function layout(RenderableResponse $response)
    {
        $this->context->block('content', $response->getContent());
        $response->setContent($this->context->render('layouts/main.html.yui@Zhuiwei'));
    }

    public static function registerListeners()
    {
        return array(
            'kernel.renderable.layout@menu-group:zhuiwei' => 'layout',
            'kernel.assets@menu-group:zhuiwei' => 'registerCoreAssets',
        );
    }
}