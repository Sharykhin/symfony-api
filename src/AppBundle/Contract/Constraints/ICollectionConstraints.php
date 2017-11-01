<?php

namespace AppBundle\Contract\Constraints;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Interface ICollectionConstraints
 * @package AppBundle\Contract\Constraints
 */
interface ICollectionConstraints
{
    /**
     * @return Assert\Collection
     */
    public function getConstraints() :  Assert\Collection;
}
