<?php

namespace Youngx\Bundle\KernelBundle\Controller;

use Youngx\MVC\Context;

class IndexController
{
    public function indexAction(Context $context)
    {
        return $context->renderResponse('html.yui@Kernel');
    }

    public function renderAction(Context $context, $path, array $variables = array())
    {
        return $context->renderResponse($path, $variables);
    }
}
