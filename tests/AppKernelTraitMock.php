<?php

namespace Netpromotion\SymfonyUp\Test;

use Netpromotion\SymfonyUp\AppKernelTrait;

class AppKernelTraitMock
{
    use AppKernelTrait;

    public function getProjectDir()
    {
        return '/tmp';
    }

    public function getEnvironment()
    {
        return 'env';
    }
}
