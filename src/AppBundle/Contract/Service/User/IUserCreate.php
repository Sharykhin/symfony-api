<?php

namespace AppBundle\Contract\Service\User;

use AppBundle\Contract\Entity\IUser;

/**
 * Interface IUserCreate
 * @package AppBundle\Contract\Service\User
 */
interface IUserCreate
{
    /**
     * @param array $parameters
     * @return IUser
     */
    public function execute(array $parameters) : IUser;
}
