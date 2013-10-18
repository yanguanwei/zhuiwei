<?php

namespace Youngx\Bundle\KernelBundle\Listener;

use Youngx\MVC\Form;
use Youngx\EventHandler\Registration;
use Youngx\MVC\Handler;

class ValidateListener implements Registration
{
    protected $handler;

    public function __construct(Handler $handler)
    {
        $this->handler = $handler;
    }

    public function validate(Form $form, $name, array $arguments, $validator)
    {
        array_unshift($arguments, "kernel.validate#{$validator}", $form->get($name));
        return call_user_func_array(array($this->handler, 'trigger'), $arguments);
    }

    public function required($value)
    {
        return !empty($value);
    }

    public function rangelength($value, $min, $max)
    {
        return !(($n = strlen($value) < $min) || $n > $max);
    }

    public function range($value, $min, $max)
    {
        $value = floatval($value);
        return $min <= $value && $value <= $max;
    }

    public function email($value)
    {
        return (Boolean) strpos($value, '#');
    }

    public function equalTo(Form $form, $name, array $arguments)
    {
        return $form->get($name) == $form->get($arguments[0]);
    }

    public function name($value)
    {
        return preg_match('/^[a-z][a-z0-9_]{3, 32}$/', $value);
    }

    public static function registerListeners()
    {
        return array(
            'kernel.validate#required' => 'required',
            'kernel.validate#rangelength' => 'rangelength',
            'kernel.validate#range' => 'range',
            'kernel.validate#email' => 'email',
            'kernel.validate#name' => 'name',
            'kernel.validate.form' => 'validate',
            'kernel.validate.form#equalTo' => 'equalTo',
        );
    }
}