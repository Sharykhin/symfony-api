<?php

namespace AppBundle\Factory\Entity;

use AppBundle\Entity\User;

/**
 * Class UserStaticFactory
 * @package AppBundle\Factory\Entity
 */
class UserStaticFactory
{
    /**
     * @return User
     */
    public static function createUser() : User
    {
        return new User();
    }
}
