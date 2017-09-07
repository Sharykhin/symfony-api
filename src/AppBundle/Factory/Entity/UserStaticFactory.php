<?php

namespace AppBundle\Factory\Entity;

use AppBundle\Contract\Entity\IUser;
use AppBundle\Entity\User;

/**
 * Class UserStaticFactory
 * @package AppBundle\Factory\Entity
 */
class UserStaticFactory
{
    /**
     * @return IUser
     */
    public static function createUser() : IUser
    {
        return new User();
    }
}
