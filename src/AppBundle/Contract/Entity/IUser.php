<?php

namespace AppBundle\Contract\Entity;

/**
 * Interface IUser
 * @package AppBundle\Contract\Entity
 */
interface IUser extends IId
{
    /**
     * @return null|string
     */
    public function getUserName() : ?string;
}