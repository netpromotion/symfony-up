<?php

namespace Netpromotion\SymfonyUp\Test;

use Netpromotion\SymfonyUp\UpTestCase;
use Netpromotion\SymfonyUp\Test\SomeApp\SomeKernel;
use Netpromotion\SymfonyUp\Test\SomeBundle\Service\SomeService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;

class UpTestCaseTest extends UpTestCase
{
    protected static function getKernelClass()
    {
        return SomeKernel::class;
    }

    public function testKernelIsKernel()
    {
        $this->assertInstanceOf(
            KernelInterface::class,
            $this->getKernel()
        );
    }

    public function testContainerIsContainer()
    {
        $this->assertInstanceOf(
            ContainerInterface::class,
            $this->getContainer()
        );
    }

    /**
     * @dataProvider dataContainerContainsServices
     * @param string $id
     * @param string $expected
     */
    public function testContainerContainsServices($id, $expected)
    {
        $this->assertInstanceOf(
            $expected,
            $this->getContainer()->get($id)
        );
    }

    public function dataContainerContainsServices()
    {
        return [
            'service from bundle' => ['some_service', SomeService::class],
            'service from test env' => ['test_service', \stdClass::class],
        ];
    }

    public function testContainerContainsEnvVariablesFromPhpunitXml()
    {
        $this->assertSame('From phpunit.xml file', $this->getContainer()->getParameter('UP_TEST_CASE'));
    }

    public function testKernelContainsRoutes()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $response = $this->getKernel()->handle(Request::create('/some.url', 'GET'));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Some response', $response->getContent());
    }
}
