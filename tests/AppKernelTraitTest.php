<?php

namespace Netpromotion\SymfonyUp\Test;

use Netpromotion\SymfonyUp\UpKernelTrait;
use PHPUnit\Framework\TestCase;

class AppKernelTraitTest extends TestCase
{
    use UpKernelTrait;

    protected $environment = 'env';

    public function getProjectDir()
    {
        return '/tmp';
    }

    public function testReturnsCorrectRootDir()
    {
        $this->assertSame('/tmp/app', (new static())->getRootDir());
    }

    public function testReturnsCorrectCacheDir()
    {
        $this->assertSame('/tmp/var/cache/env', (new static())->getCacheDir());
    }

    public function testReturnsCorrectLogDir()
    {
        $this->assertSame('/tmp/var/log', (new static())->getLogDir());
    }
}
