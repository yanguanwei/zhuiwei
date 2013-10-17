<?php

namespace Youngx\Bundle\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Youngx\Bundle\UserBundle\Form\LoginForm;
use Youngx\MVC\RenderableResponse;

class LoginController extends LoginForm
{
    public function indexAction()
    {
        return $this->run();
    }

    protected function render(RenderableResponse $response)
    {
        $this->password = '';

        $response->setFile('login.html.yui@User')
            ->addVariable('form', $this);
    }
}