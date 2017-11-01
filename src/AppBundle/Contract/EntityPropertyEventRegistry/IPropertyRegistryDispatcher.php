<?php

namespace AppBundle\Contract\EntityPropertyEventRegistry;

/**
 * Interface IPropertyRegistryDispatcher
 * @package AppBundle\Contract\EntityPropertyEventRegistry
 */
interface IPropertyRegistryDispatcher
{
    /**
     * @param string $property
     * @param $entity
     * @param array $values
     */
    public function dispatch(string $property, $entity, array $values) : void;
}
