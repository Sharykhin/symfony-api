<?php

namespace AppBundle\Contract\Service\Auth;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Interface IUserAuthenticate
 * @package AppBundle\Contract\Service\Auth
 */
interface IUserAuthenticate
{
    /**
     * @param array $parameters
     * @return UserInterface
     */
    public function authenticate(array $parameters) : UserInterface;
}
