<?php

namespace Netpromotion\SymfonyUp\Test\SomeBundle;

use Netpromotion\SymfonyUp\Test\SomeBundle\DependencyInjection\SomeExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SomeBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new SomeExtension();
    }
}
