<?php

namespace Sandbox\AnApp;

use Netpromotion\SymfonyUp\UpKernelTrait;
use Netpromotion\SymfonyUp\UpKernel;

class AKernel extends UpKernel
{
    use UpKernelTrait;

    public function getProjectDir()
    {
        return __DIR__ . '/..';
    }
}
