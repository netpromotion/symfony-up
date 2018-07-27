<?php

namespace Netpromotion\SymfonyUp\Test;

use Netpromotion\SymfonyUp\Test\AnApp\AnEnvironmentService;
use Netpromotion\SymfonyUp\Test\AnApp\APublicService;
use Netpromotion\SymfonyUp\UpTestCase;
use Netpromotion\SymfonyUp\Test\ABundle\Service\ABundleService;
use Symfony\Bundle\FrameworkBundle\Test\TestContainer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class UpTestCaseTest extends UpTestCase
{
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

    public function testContainerIsTestContainer()
    {
        $this->assertInstanceOf(
            TestContainer::class,
            $this->getContainer()
        );
    }

    /**
     * @dataProvider dataContainerContainsService
     * @param string $service
     */
    public function testContainerContainsService(string $service)
    {
        $this->assertInstanceOf(
            $service,
            $this->getContainer()->get($service)
        );
    }

    public function dataContainerContainsService()
    {
        return [
            'from app' => [APublicService::class],
            // TODO 'which is private' => [\Netpromotion\SymfonyUp\Test\AnApp\APrivateService::class],
            'from environment' => [AnEnvironmentService::class],
            'from bundle' => [ABundleService::class],
        ];
    }

    public function testContainerContainsEnvVariablesFromPhpunitXml()
    {
        $this->assertSame('From phpunit.xml file', $this->getContainer()->getParameter('UP_TEST_CASE'));
    }

    public function testKernelContainsRoutes()
    {
        $crawler = $this->createClient()->request('GET', '/a.url');

        $this->assertEquals('A response', $crawler->text());
    }
}
