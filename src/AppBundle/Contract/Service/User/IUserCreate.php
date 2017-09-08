<?php

namespace AppBundle\Contract\Service\User;

use AppBundle\Entity\User;

/**
 * Interface IUserCreate
 * @package AppBundle\Contract\Service\User
 */
interface IUserCreate
{
    /**
     * @param array $parameters
     * @return User
     */
    public function execute(array $parameters) : User;
}
