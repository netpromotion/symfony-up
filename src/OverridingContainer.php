<?php

namespace Netpromotion\SymfonyUp;

use Symfony\Component\DependencyInjection\ContainerInterface;

class OverridingContainer implements ContainerInterface
{
    /**
     * @var ContainerInterface
     */
    private $wrapped;

    /**
     * @var array
     */
    private $overridden;

    public function __construct(ContainerInterface $wrapped)
    {
        $this->wrapped = $wrapped;
        $this->overridden = [];
    }

    /**
     * @inheritdoc
     */
    public function set($id, $service)
    {
        if ( ! $this->wrapped->has($id)) {
            $this->wrapped->set($id, $service);
        } else {
            $this->overridden[$id] = $service;
        }
    }

    /**
     * @inheritdoc
     */
    public function get($id, $invalidBehavior = self::EXCEPTION_ON_INVALID_REFERENCE)
    {
        if (isset($this->overridden[$id])) {
            return $this->overridden[$id];
        }

        return $this->wrapped->get($id, $invalidBehavior);
    }

    /**
     * @inheritdoc
     */
    public function has($id)
    {
        if (array_key_exists($id, $this->overridden)) {
            return isset($this->overridden[$id]);
        }

        return $this->wrapped->has($id);
    }

    /**
     * @inheritdoc
     */
    public function initialized($id)
    {
        return isset($this->overridden[$id]) || $this->wrapped->initialized($id);
    }

    /**
     * @inheritdoc
     */
    public function getParameter($name)
    {
        return $this->wrapped->getParameter($name);
    }

    /**
     * @inheritdoc
     */
    public function hasParameter($name)
    {
        return $this->wrapped->hasParameter($name);
    }

    /**
     * @inheritdoc
     */
    public function setParameter($name, $value)
    {
        $this->wrapped->setParameter($name, $value);
    }
}
