<?php

namespace Youngx\Bundle\UserBundle\Module\RetrievePasswordModule\Entity;

use Youngx\Kernel\Database\Entity;

class UserRetrievePassword extends Entity
{
    public $uid;
    public $token;
    public $create_time;

    public static function type()
    {
        return 'user.retrieve-password';
    }
}