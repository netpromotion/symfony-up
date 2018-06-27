<?php

namespace Netpromotion\SymfonyUp\Test;

class AppKernelTraitTest extends \PHPUnit\Framework\TestCase
{
    public function testReturnsCorrectRootDir()
    {
        $this->assertSame('/tmp/app', (new AppKernelTraitMock())->getRootDir());
    }

    public function testReturnsCorrectCacheDir()
    {
        $this->assertSame('/tmp/var/cache/env', (new AppKernelTraitMock())->getCacheDir());
    }

    public function testReturnsCorrectLogDir()
    {
        $this->assertSame('/tmp/var/log', (new AppKernelTraitMock())->getLogDir());
    }
}
