<?php

namespace Youngx\Bundle\UserBundle;

use Youngx\Bundle\UserBundle\Entity\User;
use Youngx\MVC\Context;
use Youngx\MVC\User\Identity;
use Symfony\Component\HttpFoundation\Cookie;
use Youngx\MVC\Event\FilterResponseEvent;
use Youngx\MVC\User\IdentityStorageInterface;

class IdentityStorage implements IdentityStorageInterface
{
    protected $duration;
    /**
     * @var Identity
     */
    protected $identity;

    protected $key = 'YoungxUserToken';

    /**
     * @var Context
     */
    protected $context;

    public function setContext(Context $context)
    {
        $this->context = $context;
    }

    public function clear()
    {
        $this->context->session()->remove('Youngx.User.Identity');
        $this->context->handler()->addListener('kernel.response', array($this, 'onResponseForClear'));
    }

    public function onResponseForClear(FilterResponseEvent $event)
    {
        $header = $event->getResponse()->headers;
        $header->clearCookie($this->key);
    }

    public function read()
    {
        if (null === $this->identity) {
            $identity = null;
            $cookie = $this->context->request()->cookies;
            if ($cookie->has($this->key)) {
                $token = explode('.', base64_decode($cookie->get($this->key)));
                if (count($token) == 2) {
                    list($id, $encoded) = $token;
                    //if ($this->context->session()->has('Youngx.User.Identity')) {
                    //    $identity = $this->context->session()->get('Youngx.User.Identity');
                    //} else {
                        $user = $this->context->repository()->load('user', $id);
                        if ($user) {
                            $identity = Identity::createFromEntity($user);
                        }
                    //}
                    if ($identity) {
                        if ($encoded === $this->generateEncodedToken($identity)) {
                            $this->identity = $identity;
                        }
                    }
                }

                if (!$this->identity) {
                    $this->context->handler()->addListener('kernel.response', array($this, 'onResponseForClear'));
                }
            }
        }

        if (!$this->identity) {
            $this->identity = new Identity();
        }

        return $this->identity;
    }

    public function write(Identity $identity, $duration)
    {
        $this->identity = $identity;
        $this->duration = $duration;
        //$this->context->session()->set('Youngx.User.Identity', $identity);
        $this->context->handler()->addListener('kernel.response', array($this, 'onResponseForWrite'));
    }

    public function onResponseForWrite(FilterResponseEvent $event)
    {
        $header = $event->getResponse()->headers;

        if ($this->identity) {
            $token = $this->generateLoggedToken($this->identity);
            if ($this->duration) {
                $expire = Y_TIME + $this->duration;
                $header->setCookie(new Cookie($this->key, $token, $expire));
            } else {
                $header->setCookie(new Cookie($this->key, $token));
            }
        }
    }

    protected function getSalt()
    {
        return $this->context->config('user:login.salt', '');
    }

    protected function generateLoggedToken(Identity $identity)
    {
        return base64_encode("{$identity->getId()}.{$this->generateEncodedToken($identity)}");
    }

    protected function generateEncodedToken(Identity $identity)
    {
        $token = $identity->getId() . $identity->getName() . $identity->getPassword() . $this->getSalt() . $this->context->request()->server->get('HTTP_USER_AGENT');
        return md5($token);
    }
}