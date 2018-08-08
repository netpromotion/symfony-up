<?php

namespace Netpromotion\SymfonyUp\Test\Console\Message;

use Netpromotion\SymfonyUp\Console\Message\AddEnvironment;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class AddEnvironmentTest extends TestCase
{
    private function getKernel(): KernelInterface
    {
        $kernel = $this->getMockBuilder(KernelInterface::class)->getMock();

        $kernel->method('getEnvironment')->willReturn('test');
        $kernel->method('isDebug')->willReturn(true);

        /** @var KernelInterface $kernel */
        return $kernel;
    }

    public function testAddsColoredEnvironmentToMessage()
    {
        $this->assertEquals(
            'It works for the <info>test</info> environment with debug <info>true</info>.',
            AddEnvironment::colored($this->getKernel(), 'It works %s.')
        );
    }

    public function testAddsPlainEnvironmentToMessage()
    {
        $this->assertEquals(
            'It works for the "test" environment (debug=true).',
            AddEnvironment::plain($this->getKernel(), 'It works %s.')
        );
    }
}
