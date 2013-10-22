<?php

namespace Youngx\Bundle\KernelBundle\Controller;

use Youngx\Util\Captcha\Captcha;
use Youngx\MVC\Context;

class IndexController
{
    public function indexAction(Context $context)
    {
        return $context->renderResponse('html.yui@Kernel');
    }

    public function captchaAction(Context $context, $id = null)
    {
        $captcha = new Captcha($id);
        $response = $context->response($captcha->create(false));
        $response->headers->set('Content-Type', 'image/png');
        return $response;
    }

    public function renderAction(Context $context, $path, array $variables = array())
    {
        return $context->renderResponse($path, $variables);
    }
}
