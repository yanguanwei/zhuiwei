<?php

namespace Youngx\Bundle\ZhuiweiBundle\Listener;

use Youngx\EventHandler\Registration;
use Youngx\MVC\Menu\MenuCollection;

class CollectListener implements Registration
{
    public function collectMenu(MenuCollection $collection)
    {
        $collection->add('user-logout', '/logout', '退出', 'Logout@User')
            ->setAccess('user-logout');

        $collection->add('user-home', '/user', '我的超值存货空间', 'User@Zhuiwei')
            ->setAccess('registered');

        $zhuiwei = $collection->getCollection('zhuiwei');
        $zhuiwei->add('home', '/', '首页', 'Home@Zhuiwei')
            ->setAccess(true);

        $zhuiwei->add('user-email-activation', '/user/activation-email/{token}', '邮箱激活', 'EmailActivation@Zhuiwei')
            ->setAccess(true);

        $zhuiwei->add('user-login', '/login', '登录', 'Front::login@Zhuiwei')
            ->setAccess('user-login');

        $zhuiwei->add('user-register-buyer', '/register/buyer', '买家注册', 'Front::buyerRegister@Zhuiwei')
            ->setAccess('user-register');

        $zhuiwei->add('user-register-seller', '/register/seller', '卖家注册', 'Front::sellerRegister@Zhuiwei')
            ->setAccess('user-register');

        $zhuiwei->add('user-register-verify', '/register/verify/{user}', '注册验证', 'Front::registerVerify@Zhuiwei')
            ->setRequirement('user', '\d+', 'user')
            ->setAccess('user-register');
        $zhuiwei->add('user-register-success', '/register/success/{user}', '注册成功', 'Front::registerSuccess@Zhuiwei')
            ->setRequirement('user', '\d+', 'user')
            ->setAccess('user-register');

        $zhuiwei->add('products', '/products', '所有产品', 'Products@Zhuiwei')
            ->setAccess(true);

        $zhuiwei->add('product-detail', '/product/{product}', '产品详细页', 'ProductDetail@Zhuiwei')
            ->setAccess(true)
            ->setRequirement('product', '\d+', 'product');;

        $zhuiwei->add('platform', '/platform/{news}', '平台服务', 'Front::platform@Zhuiwei')
            ->setRequirement('news', '\d+', 'news')
            ->setDefault('news', 0)
            ->setAccess(true);

        $buyer = $zhuiwei->getCollection('buyer');
        $buyer->add('buyer-home', '/user/buyer', '买家中心', 'Buyer@Zhuiwei');

        $seller = $zhuiwei->getCollection('seller');
        $seller->add('seller-home', '/user/seller', '卖家中心', 'Seller@Zhuiwei');
        $seller->add('seller-products', '/user/seller/products', '产品列表', 'Seller::products@Zhuiwei');

        $seller->add('seller-product-edit', '/user/seller/product/{product}/edit', '编辑产品', 'Seller::editProduct@Zhuiwei')
            ->setRequirement('product', '\d+', 'product');

        $seller->add('seller-product-add', '/user/seller/product/add', '添加产品', 'Seller::addProduct@Zhuiwei');

    }

    public static function registerListeners()
    {
        return array(
            'kernel.menu.collect' => 'collectMenu'
        );
    }
}