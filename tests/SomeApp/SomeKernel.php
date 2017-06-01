<?php

namespace Netpromotion\SymfonyUp\Test\SomeApp;

use Netpromotion\SymfonyUp\Kernel\AppKernel;
use Netpromotion\SymfonyUp\Test\SomeBundle\SomeBundle;
use Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;

class SomeKernel extends AppKernel
{
    /**
     * @inheritdoc
     */
    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
            new SensioFrameworkExtraBundle(),
            new SomeBundle()
        ];
    }
}
