<?php

namespace Youngx\Bundle\ZhuiweiBundle\Controller;

use Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Entity\ProductEntity;
use Youngx\MVC\Context;

class SellerController
{
    public function indexAction(Context $context)
    {
        $context->assets()->registerStyleUrl('seller', 'Zhuiwei/css/M_4.css');
        return $context->renderableResponse();
    }

    public function productsAction(Context $context)
    {
        return $context->actionResponse('Action:SellerProducts@Zhuiwei');
    }

    public function addProductAction(Context $context)
    {
        return $context->actionResponse('Form:SellerProduct@Zhuiwei', array(
                'user' => $context->identity()->getUserEntity()
            ));
    }

    public function editProductAction(Context $context, ProductEntity $product)
    {
        return $context->actionResponse('Form:SellerProduct@Zhuiwei', array(
                'product' => $product
            ));
    }
}