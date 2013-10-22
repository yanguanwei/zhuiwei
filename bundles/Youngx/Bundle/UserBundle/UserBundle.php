<?php

namespace Youngx\Bundle\UserBundle;

use Youngx\MVC\Bundle;

class UserBundle extends Bundle
{
    public function modules()
    {
        return array(
            //'Alipay', 'RetrievePassword', 'Activation'
            'Activation'
        );
    }
}
