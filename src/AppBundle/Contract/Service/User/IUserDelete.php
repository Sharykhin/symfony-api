<?php

namespace AppBundle\Contract\Service\User;

use AppBundle\Entity\User;

/**
 * Interface IUserDelete
 * @package AppBundle\Contract\Service\User
 */
interface IUserDelete
{
    /**
     * @param User $user
     */
    public function execute(User $user) : void;
}
