<?php

namespace Netpromotion\SymfonyUp;

trait AppKernelTrait
{
    /**
     * Gets the application root dir.
     *
     * @return string
     */
    abstract public function getProjectDir();

    /**
     * Gets the environment.
     *
     * @return string
     */
    abstract public function getEnvironment();

    public function getRootDir()
    {
        return $this->getProjectDir() . '/app';
    }

    public function getCacheDir()
    {
        return $this->getProjectDir() . '/var/cache/' . $this->getEnvironment();
    }

    public function getLogDir()
    {
        return $this->getProjectDir() . '/var/logs';
    }
}
