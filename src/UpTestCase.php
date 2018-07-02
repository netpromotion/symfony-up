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
        static::$container = null;
    }

    protected function getKernel(): KernelInterface
    {
        $this->bootKernelIfNeeded();

        return static::$kernel;
    }

    protected function getContainer(): ContainerInterface
    {
        $this->bootKernelIfNeeded();

        return static::$container;
    }

    private function bootKernelIfNeeded()
    {
        if (!static::$kernel || !static::$container) {
            static::bootKernel();
        }
    }
}
