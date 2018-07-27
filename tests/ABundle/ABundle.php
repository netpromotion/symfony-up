<?php

namespace Netpromotion\SymfonyUp\Test\ABundle;

use Netpromotion\SymfonyUp\Test\ABundle\DependencyInjection\AnExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ABundle extends Bundle
{
    public function getContainerExtension()
    {
        return new AnExtension();
    }
}
