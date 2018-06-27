<?php

namespace Netpromotion\SymfonyUp;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class UpTestCase extends WebTestCase
{
    /**
     * @inheritdoc
     */
    protected static function getKernelClass()
    {
        throw new \LogicException(sprintf('Override %s method', __METHOD__));
    }

    /**
     * @inheritdoc
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        static::$class = null;
        static::$kernel = null;
    }

    protected function getKernel(): KernelInterface
    {
        if (!static::$kernel || !static::$kernel->getContainer()) {
            static::bootKernel();
        }
        return static::$kernel;
    }

    protected function getContainer(): ContainerInterface
    {
        return $this->getKernel()->getContainer();
    }
}
