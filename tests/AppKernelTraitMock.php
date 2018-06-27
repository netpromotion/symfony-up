<?php

namespace Netpromotion\SymfonyUp\Test;

use Netpromotion\SymfonyUp\UpKernelTrait;

class AppKernelTraitMock
{
    use UpKernelTrait;

    protected $environment = 'env';

    public function getProjectDir()
    {
        return '/tmp';
    }
}
