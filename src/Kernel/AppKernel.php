<?php

namespace Netpromotion\SymfonyUp\Kernel;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

abstract class AppKernel extends Kernel
{
    use MicroKernelTrait;

    /**
     * @inheritdoc
     */
    protected function configureContainer(ContainerBuilder $containerBuilder, LoaderInterface $loader)
    {
        $config = $this->getRootDir() . '/config/config.yml';
        if (file_exists($config)) {
            $loader->load($config);
        } else {
            throw new \LogicException(sprintf('Create %s or override %s method', $config, __METHOD__));
        }

        $envConfig = $this->getRootDir() . '/config/config_' . $this->getEnvironment() . '.yml';
        if (file_exists($envConfig)) {
            $loader->load($envConfig);
        }
    }

    /**
     * @inheritdoc
     */
    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        $routing = $this->getRootDir() . '/config/routing.yml';
        if (file_exists($routing)) {
            $routes->import($routing);
        }

        $envRouting = $this->getRootDir() . '/config/routing_' . $this->getEnvironment() . '.yml';
        if (file_exists($envRouting)) {
            $routes->import($envRouting);
        }

        return $routes;
    }
}
