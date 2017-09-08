<?php

namespace AppBundle\Factory\Entity;

use AppBundle\Contract\Factory\Entity\IUserFactory;
use AppBundle\Entity\User;

/**
 * Class UserFactory
 * @package AppBundle\Factory\Entity
 */
class UserFactory implements IUserFactory
{
    /**
     * @return User
     */
    public function createUser() : User
    {
        return new User();
    }
}
