<?php

namespace Youngx\Bundle\ZhuiweiBundle\Controller;

use Youngx\MVC\Context;

class HomeController
{
    public function indexAction(Context $context)
    {
        return $context->response();
    }
}