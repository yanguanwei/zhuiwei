<?php

namespace Youngx\Bundle\UserBundle\Form;

use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\User\Identity;
use Youngx\MVC\Form;

class LoginForm extends Form
{
    protected $username;
    protected $password;
    protected $rememberMe;
    protected $returnUrl;

    public function id()
    {
        return 'user-login';
    }

    public function setUsername($username)
    {
        $this->username = trim($username);
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $rememberMe
     */
    public function setRememberMe($rememberMe)
    {
        $this->rememberMe = $rememberMe;
    }

    /**
     * @return mixed
     */
    public function getRememberMe()
    {
        return $this->rememberMe;
    }

    /**
     * @param mixed $returnUrl
     */
    public function setReturnUrl($returnUrl)
    {
        $this->returnUrl = $returnUrl;
    }

    /**
     * @return mixed
     */
    public function getReturnUrl()
    {
        return $this->returnUrl;
    }

    protected function registerValidators()
    {
        return array(
            'username' => array(
                'required' => '用户名不能为空！'
            ),
            'password' => array(
                'required' => '密码不能为空！'
            )
        );
    }

    protected function validate(Form\FormErrorHandler $feh)
    {
        $context = $this->context;
        $user = $context->repository()->query('user')->findByName($this->username);
        if ($user && $user->getPassword() === $user->encryptPassword($this->password)) {
            $context->login(Identity::createFromEntity($user), $this->rememberMe ? 86400 * 365 : 0);
        } else {
            $context->flash()->add('error', '用户名或密码错误！');
            $feh->add('password', '用户或密码错误！');
        }
    }

    protected function submit(GetResponseEvent $event)
    {
        $returnUrl = $this->returnUrl ?: $this->computeRedirectUrl();
        $event->setResponse($this->context->redirectResponse($returnUrl));
    }

    protected function computeRedirectUrl()
    {
        return $this->context->generateUrl('user-home');
    }

    protected function render(RenderableResponse $response)
    {
        $response->setFile('login.html.yui@User')
            ->addVariable('form', $this);
    }
}