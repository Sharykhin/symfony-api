<?php

namespace AppBundle\Contract\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Interface IAdvancedUser
 * @package AppBundle\Contract\Entity
 */
interface IAdvancedUser extends IUser, UserInterface
{

}