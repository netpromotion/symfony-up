<?php

namespace Netpromotion\SymfonyUp\Test;

use Netpromotion\SymfonyUp\Test\SomeApp\SomeKernel;
use Netpromotion\SymfonyUp\Test\SomeBundle\Service\SomeService;
use Netpromotion\SymfonyUp\TestCase\AppTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Client;

class SymfonyTestCaseTest extends AppTestCase
{
    protected static function getKernelClass()
    {
        return SomeKernel::class;
    }

    public function testContainsContainer()
    {
        $this->assertInstanceOf(
            ContainerInterface::class,
            $this->getKernel()->getContainer()
        );
    }

    /**
     * @dataProvider dataContainsServices
     * @param string $id
     * @param string $expected
     */
    public function testContainsServices($id, $expected)
    {
        $this->assertInstanceOf(
            $expected,
            $this->getKernel()->getContainer()->get($id)
        );
    }

    public function dataContainsServices()
    {
        return [
            ['some_service', SomeService::class], // service from bundle
            ['test_service', \stdClass::class], // service from tests
        ];
    }

    public function testContainsRoutes()
    {
        $client = new Client($this->getKernel());
        $client->request('GET', '/some.url');

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Some response', $response->getContent());
    }
}
