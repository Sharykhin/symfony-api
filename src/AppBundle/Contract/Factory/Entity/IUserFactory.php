<?php

namespace AppBundle\Contract\Factory\Entity;

use AppBundle\Entity\User;

/**
 * Interface IUserFactory
 * @package AppBundle\Contract\Factory\Entity
 */
interface IUserFactory
{
    /**
     * @return User
     */
    public function createUser() : User;
}