<?php

namespace AppBundle\EntityPropertyEventRegistry\User;

use AppBundle\Entity\User;

/**
 * Class OnFirstNameChanged
 * @package AppBundle\EntityPropertyEventRegistry\User
 */
class OnFirstNameChanged
{
    /**
     * @param User $user
     * @param string $oldValue
     * @param string $newValue
     */
    public function execute(User $user, string $oldValue, string $newValue) : void
    {
       var_dump('ha ha ha');
    }
}
