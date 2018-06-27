<?php

namespace Netpromotion\SymfonyUp\Test\SomeApp;

use Netpromotion\SymfonyUp\UpKernelTrait;
use Netpromotion\SymfonyUp\UpKernel;

class SomeKernel extends UpKernel
{
    use UpKernelTrait;

    public function getProjectDir()
    {
        return __DIR__;
    }
}
