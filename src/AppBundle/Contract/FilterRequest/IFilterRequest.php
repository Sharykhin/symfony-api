<?php

namespace AppBundle\Contract\FilterRequest;

/**
 * Interface IFilterRequest
 * @package AppBundle\Contract\FilterRequest
 */
interface IFilterRequest
{
    /**
     * @param string $action
     * @return bool
     */
    public function isSupport(string $action) : bool;

    /**
     * @param array $parameters
     * @param string $type
     * @return array
     */
    public function filterRequest(array $parameters, string $type) : array;
}
