<?php

namespace Netpromotion\SymfonyUp;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

abstract class UpKernel extends Kernel
{
    use MicroKernelTrait;

    /**
     * @inheritdoc
     */
    protected function configureContainer(ContainerBuilder $containerBuilder, LoaderInterface $loader)
    {
        $envConfig = $this->getRootDir() . '/config/config_' . $this->getEnvironment() . '.yml';
        if (file_exists($envConfig)) {
            $loader->load($envConfig);
        } else {
            $config = $this->getRootDir() . '/config/config.yml';
            if (file_exists($config)) {
                $loader->load($config);
            } else {
                throw new \LogicException(sprintf('Create %s or override %s method', $config, __METHOD__));
            }
        }
    }

    /**
     * @inheritdoc
     */
    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        $envRouting = $this->getRootDir() . '/config/routing_' . $this->getEnvironment() . '.yml';
        if (file_exists($envRouting)) {
            $routes->import($envRouting);
        } else {
            $routing = $this->getRootDir() . '/config/routing.yml';
            if (file_exists($routing)) {
                $routes->import($routing);
            }
        }

        return $routes;
    }
}
