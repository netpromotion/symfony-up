<?php

namespace Netpromotion\SymfonyUp\Test;

use Netpromotion\SymfonyUp\UpTestCase;
use Netpromotion\SymfonyUp\Test\SomeApp\SomeKernel;
use Netpromotion\SymfonyUp\Test\SomeBundle\Service\SomeService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class UpTestCaseTest extends UpTestCase
{
    protected static function getKernelClass()
    {
        return SomeKernel::class;
    }

    public function testKernelContainsContainer()
    {
        $this->assertInstanceOf(
            ContainerInterface::class,
            $this->getKernel()->getContainer()
        );
    }

    /**
     * @dataProvider dataKernelContainsServices
     * @param string $id
     * @param string $expected
     */
    public function testKernelContainsServices($id, $expected)
    {
        $this->assertInstanceOf(
            $expected,
            $this->getKernel()->getContainer()->get($id)
        );
    }

    public function dataKernelContainsServices()
    {
        return [
            ['some_service', SomeService::class], // service from bundle
            ['test_service', \stdClass::class], // service from tests
        ];
    }

    public function testKernelContainsRoutes()
    {
        $response = $this->getKernel()->handle(Request::create('/some.url', 'GET'));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Some response', $response->getContent());
    }
}
