<?php

namespace Netpromotion\SymfonyUp;

trait UpKernelTrait
{
    abstract public function getProjectDir(): string;

    public function getRootDir(): string
    {
        return $this->getProjectDir() . '/app';
    }

    public function getCacheDir(): string
    {
        return $this->getProjectDir() . '/var/cache/' . $this->environment;
    }

    public function getLogDir(): string
    {
        return $this->getProjectDir() . '/var/log';
    }
}
