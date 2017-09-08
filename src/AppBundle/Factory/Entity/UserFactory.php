<?php

namespace AppBundle\Factory\Entity;

use AppBundle\Entity\User;

/**
 * Class UserFactory
 * @package AppBundle\Factory\Entity
 */
class UserFactory
{
    /**
     * @return User
     */
    public function createUser() : User
    {
        return new User();
    }
}
