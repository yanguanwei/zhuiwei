<?php

namespace Youngx\Bundle\KernelBundle\Listener;

use Symfony\Component\Routing\Generator\UrlGenerator;
use Youngx\EventHandler\Event\GetSortableArrayEvent;
use Youngx\EventHandler\Event\GetValueEvent;
use Youngx\EventHandler\Registration;
use Youngx\MVC\Context;
use Youngx\MVC\Form;
use Youngx\MVC\ListView\TableListView;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\Templating\TemplateInterface;
use Youngx\MVC\Yui\Block;
use Youngx\MVC\Yui\YuiEngine;

class TemplatingListener implements Registration
{

    /**
     * @var YuiEngine
     */
    protected $engine;

    /**
     * @var Context
     */
    protected $context;

    public function __construct(YuiEngine $engine)
    {
        $this->engine = $engine;
    }

    public function setContext(Context $context)
    {
        $this->context = $context;
    }

    public function assets()
    {
        return $this->context->assets();
    }

    public function asset_script($key, $path, $sort = 0)
    {
        return $this->context->assets()->registerScriptUrl($key, $this->asset_url($path), $sort);
    }

    public function asset_style($key, $path, $sort = 0)
    {
        return $this->context->assets()->registerStyleUrl($key, $this->asset_url($path), $sort);
    }

    public function asset_style_code($key, $code, $sort = 0)
    {
        return $this->context->assets()->registerStyleCode($key, $code, $sort);
    }

    public function asset_script_code($key, $code, $sort = 0)
    {
        return $this->context->assets()->registerScriptCode($key, $code, $sort);
    }

    public function asset_package($package, $version = null)
    {
        return $this->context->assets()->registerPackage($package, $version);
    }

    public function asset_url($path)
    {
        if ($path[0] == '/') {
            $path = substr($path, 1);
        } else {
            $prefix = $this->engine->getModule() ?: $this->engine->getBundle()->getName();
            $path = "{$prefix}/{$path}";
        }

        return $this->context->assetUrl($path);
    }

    public function block($name, $content = null)
    {
        return $this->engine->block($name, $content);
    }

    public function cache()
    {
        $cache = $this->context->cache();
        $args = func_get_args();
        if (!$args) {
            return $cache;
        }

        switch (count($args)) {
            case 1:
                return $cache->fetch($args[0]);
            case 2:
                $cache->save($args[0], $args[1]);
                return $cache;
            case 3:
                $cache->save($args[0], $args[1], $args[2]);
                return $cache;
        }

    }

    public function html($html, array $attributes = array())
    {
        return $this->context->html($html, $attributes);
    }

    public function widget($name, array $config = array())
    {
        return $this->context->widget($name, $config);
    }

    public function query($key, $default = null)
    {
        return $this->context->request()->query->get($key, $default);
    }

    public function post($key, $default = null)
    {
        return $this->context->request()->request->get($key, $default);
    }

    public function request()
    {
        return $this->context->request();
    }

    public function cookie($key, $default = null)
    {
        return $this->context->request()->cookies->get($key, $default);
    }

    public function session($key, $default = null)
    {
        return $this->context->session()->get($key, $default);
    }

    public function server($key, $default = null)
    {
        return $this->context->request()->server->get($key, $default);
    }

    public function url($name, array $parameters = array(), $referenceType = UrlGenerator::ABSOLUTE_PATH)
    {
        return $this->context->generateUrl($name, $parameters, $referenceType);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface
     */
    protected function flash()
    {
        return $this->context->flash();
    }

    public function flash_messages()
    {
        return $this->flash()->all();
    }

    public function layout(RenderableResponse $response)
    {
        $this->block('body', $response->getContent());
        $response->setContent($this->context->render('layouts/html.yui@Kernel'));
    }

    public function identity()
    {
        return $this->context->identity();
    }

    public function repository()
    {
        return $this->context->repository();
    }

    public static function registerListeners()
    {
        return array(
            'kernel.templating.call.asset_style' => 'asset_style',
            'kernel.templating.call.asset_style_code' => 'asset_style_code',
            'kernel.templating.call.asset_script_code' => 'asset_script_code',
            'kernel.templating.call.asset_script' => 'asset_script',
            'kernel.templating.call.asset_url' => 'asset_url',
            'kernel.templating.call.asset_package' => 'asset_package',
            'kernel.templating.call.assets' => 'assets',
            'kernel.templating.call.cache' => 'cache',
            'kernel.templating.call.block' => 'block',
            'kernel.templating.call.form' => 'form',
            'kernel.templating.call.html' => 'html',
            'kernel.templating.call.table' => 'table',
            'kernel.templating.call.tab' => 'tab',
            'kernel.templating.call.widget' => 'widget',
            'kernel.templating.call.query' => 'query',
            'kernel.templating.call.request' => 'request',
            'kernel.templating.call.cookie' => 'cookie',
            'kernel.templating.call.session' => 'session',
            'kernel.templating.call.server' => 'server',
            'kernel.templating.call.url' => 'url',
            'kernel.templating.call.flash_messages' => 'flash_messages',
            'kernel.templating.call.identity' => 'identity',
            'kernel.templating.call.repository' => 'repository',
            'kernel.renderable.layout' => 'layout',
        );
    }
}