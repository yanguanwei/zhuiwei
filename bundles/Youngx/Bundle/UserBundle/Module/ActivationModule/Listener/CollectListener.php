<?php

namespace Youngx\Bundle\UserBundle\Module\ActivationModule\Listener;

use Youngx\Kernel\Database\EntityCollection;
use Youngx\Kernel\Database\Schema;
use Youngx\Kernel\Handler\ListenerRegistration;

class CollectListener implements ListenerRegistration
{
    public function collectEntity(EntityCollection $collection)
    {
        $collection->add('Youngx\Bundle\UserBundle\Module\ActivationModule\Entity\UserActivation', 'id', 'y_user_activation')
            ->relate('user', 'user', array('uid' => 'uid'), Schema::ONE_MANY, 'activations');
    }

    public static function registerListeners()
    {
        return array(
            'kernel.entity.collect' => 'collectEntity'
        );
    }
}