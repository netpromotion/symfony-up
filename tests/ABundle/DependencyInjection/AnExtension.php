<?php

namespace Netpromotion\SymfonyUp\Test\ABundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class AnExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        /** @noinspection PhpUnhandledExceptionInspection */
        $loader->load('config.yml');
    }

    public function getAlias()
    {
        return 'A';
    }
}

