<?php

namespace Netpromotion\SymfonyUp\TestCase;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class AppTestCase extends WebTestCase
{
    protected static function getKernelClass()
    {
        throw new \LogicException(sprintf('Override %s method', __METHOD__));
    }

    /**
     * @return KernelInterface
     */
    protected function getKernel()
    {
        if (!self::$kernel || !self::$kernel->getContainer()) {
            self::bootKernel();
        }
        return self::$kernel;
    }

    /**
     * @return ContainerInterface
     */
    protected function getContainer()
    {
        return $this->getKernel()->getContainer();
    }
}
