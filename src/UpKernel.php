<?php

namespace Netpromotion\SymfonyUp;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Exception\FileLoaderLoadException;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

abstract class UpKernel extends Kernel
{
    use MicroKernelTrait;

    const CONFIG_EXTS = '.{php,xml,yaml,yml}';

    /**
     * @inheritdoc
     */
    public function getName()
    {
        $name = str_replace('\\', '_', get_called_class());
        $name = preg_replace('/Kernel$/', '', $name);

        return $name;
    }

    public function registerBundles()
    {
        /** @noinspection PhpIncludeInspection */
        $bundles = require $this->getProjectDir() . '/config/bundles.php';
        foreach ($bundles as $class => $environments) {
            if (isset($environments['all']) || isset($environments[$this->environment])) {
                yield new $class();
            }
        }
    }

    private function getConfigurationDir()
    {
        return $this->getProjectDir().'/config';
    }

    /**
     * @param ContainerBuilder $container
     * @param LoaderInterface $loader
     * @throws \Exception
     */
    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader)
    {
        $container->addResource(new FileResource($this->getProjectDir().'/config/bundles.php'));
        $container->setParameter('container.dumper.inline_class_loader', true);

        $loader->load($this->getConfigurationDir() . '/{packages}/*' . self::CONFIG_EXTS, 'glob');
        $loader->load($this->getConfigurationDir() . '/{packages}/' . $this->environment . '/*' . self::CONFIG_EXTS, 'glob');
        $loader->load($this->getConfigurationDir() . '/{config}' . self::CONFIG_EXTS, 'glob');
        $loader->load($this->getConfigurationDir() . '/{config}_' . $this->environment . self::CONFIG_EXTS, 'glob');
        $loader->load($this->getConfigurationDir() . '/{services}' . self::CONFIG_EXTS, 'glob');
        $loader->load($this->getConfigurationDir() . '/{services}_' . $this->environment . self::CONFIG_EXTS, 'glob');
    }

    /**
     * @param RouteCollectionBuilder $routes
     * @throws FileLoaderLoadException
     */
    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        $routes->import($this->getConfigurationDir() . '/{routes}/*' . self::CONFIG_EXTS, '/', 'glob');
        $routes->import($this->getConfigurationDir() . '/{routes}/' . $this->environment . '/*' . self::CONFIG_EXTS, '/', 'glob');
        $routes->import($this->getConfigurationDir() . '/{routes}' . self::CONFIG_EXTS, '/', 'glob');
        $routes->import($this->getConfigurationDir() . '/{routes}_' . $this->environment . self::CONFIG_EXTS, '/', 'glob');
    }
}
