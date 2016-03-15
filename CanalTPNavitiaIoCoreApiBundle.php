<?php

namespace CanalTP\NavitiaIoCoreApiBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CanalTPNavitiaIoCoreApiBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
