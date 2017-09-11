<?php

namespace AppBundle\Contract\Service\Auth;

use AppBundle\Contract\Entity\IAdvancedUser;

/**
 * Interface IUserAuthenticate
 * @package AppBundle\Contract\Service\Auth
 */
interface IUserAuthenticate
{
    /**
     * @param array $parameters
     * @return IAdvancedUser
     */
    public function execute(array $parameters) : IAdvancedUser;
}
