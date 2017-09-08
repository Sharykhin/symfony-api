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

    /** @var string|null $username  */
    private $username;

    /** @var  string|null $firstName */
    private $firstName;

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

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName(string $firstName = null)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName() : ?string
    {
        return $this->firstName;
    }
}
