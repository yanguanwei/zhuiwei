<?php

namespace Youngx\Bundle\ZhuiweiBundle\Controller;

use Youngx\MVC\Context;

class BuyerController
{
    public function indexAction(Context $context)
    {
        $context->assets()->registerStyleUrl('buyer', 'Zhuiwei/css/mai_1.css');
        return $context->renderableResponse();
    }
}