<?php

namespace Youngx\Bundle\KernelBundle;

use Youngx\MVC\Bundle;

class KernelBundle extends Bundle
{
    public function modules()
    {
        return array(
            'Bootstrap', 'CKEditor', 'CKFinder', 'UEditor'
        );
    }
}