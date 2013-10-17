<?php

namespace Youngx\Bundle\UserBundle\Module\ActivationModule\Entity;

use Youngx\Kernel\Database\Entity;

class UserActivation extends Entity
{
    const TYPE_EMAIL = 0;
    const TYPE_PHONE = 1;

    public $id;
    public $uid;
    public $type;
    public $value;
    public $token;
    public $is_activated;
    public $create_time;

    public static function type()
    {
        return 'user.activation';
    }
}