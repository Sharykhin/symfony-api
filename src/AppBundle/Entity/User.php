<?php

namespace AppBundle\Entity;

use AppBundle\Contract\Entity\IUser;

/**
 * Class User
 * @package AppBundle\Entity
 */
class User implements IUser
{
    /** @var  $id */
    private $id;

    /** @var string|null $username */
    private $username;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername(string $username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername() : ?string
    {
        return $this->username;
    }
}
