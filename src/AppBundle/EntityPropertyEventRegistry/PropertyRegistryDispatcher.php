<?php

namespace AppBundle\EntityPropertyEventRegistry;

use AppBundle\Contract\EntityPropertyEventRegistry\IPropertyRegistryDispatcher;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

/**
 * Class PropertyRegistryDispatcher
 * @package AppBundle\EntityPropertyEventRegistry
 */
class PropertyRegistryDispatcher implements IPropertyRegistryDispatcher
{
    const REGISTRY_PATH = 'AppBundle\\EntityPropertyEventRegistry';

    /** @var ContainerInterface $container */
    protected $container;

    /**
     * PropertyRegistryDispatcher constructor.
     * @param ContainerInterface $container
     */
    public function __construct(
        ContainerInterface $container
    )
    {
        $this->container = $container;
    }

    /**
     * @param string $property
     * @param $entity
     * @param array $values
     */
    public function dispatch(string $property, $entity, array $values) : void
    {
        $event = 'On' . ucfirst($property). 'Changed';
        $entityName = (new \ReflectionClass($entity))->getShortName();
        $servicePath = self::REGISTRY_PATH . "\\$entityName\\$event";
        if (class_exists($servicePath)) {
            if (!$this->container->has($servicePath)) {
                throw new ServiceNotFoundException($servicePath);
            }

            $service = $this->container->get($servicePath);
            $service->execute($entity, ...$values);
        }
    }
}