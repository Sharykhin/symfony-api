<?php

namespace AppBundle\Contract\Service\User;

use AppBundle\Entity\User;

/**
 * Interface IUserUpdate
 * @package AppBundle\Contract\Service\User
 */
interface IUserUpdate
{
    /**
     * @param User $user
     * @param array $parameters
     * @return User
     */
    public function execute(User $user, array $parameters) : User;
}
