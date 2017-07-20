<?php

namespace Netpromotion\SymfonyUp\Test;

use Netpromotion\SymfonyUp\OverridingContainer;
use Symfony\Component\DependencyInjection\ContainerInterface;

class OverridingContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataCallsWrappedContainer
     * @param string $method
     * @param array $args
     */
    public function testCallsWrappedContainer($method, $args)
    {
        $container = $this->getMockBuilder(ContainerInterface::class)->getMock();
        $container->expects($this->once())->method($method)->willReturnCallback(function () use ($args) {
            $this->assertEquals($args, func_get_args());
        });

        /** @var ContainerInterface $container */
        call_user_func_array([new OverridingContainer($container), $method], $args);
    }

    public function dataCallsWrappedContainer()
    {
        return [
            ['set', ['$id', '$service']],
            ['get', ['$id', '$invalidBehavior']],
            ['has', ['$id']],
            ['initialized', ['$id']],
            ['getParameter', ['$name']],
            ['hasParameter', ['$name']],
            ['setParameter', ['$name', '$value']],
        ];
    }

    public function testCanOverrideService()
    {
        $container = $this->getMockBuilder(ContainerInterface::class)->getMock();
        $container->method('has')->willReturn(true);
        $container->method('get')->willReturn('$service');
        $service = new \stdClass();

        /** @var ContainerInterface $container */
        $container = new OverridingContainer($container);

        $this->assertEquals('$service', $container->get('$id'));
        $container->set('$id', $service);
        $this->assertEquals($service, $container->get('$id'));
    }

    public function testCanUnsetService()
    {
        $container = $this->getMockBuilder(ContainerInterface::class)->getMock();
        $container->method('has')->willReturn(true);

        /** @var ContainerInterface $container */
        $container = new OverridingContainer($container);

        $this->assertTrue($container->has('$id'));
        $container->set('$id', null);
        $this->assertFalse($container->has('$id'));
    }
}
