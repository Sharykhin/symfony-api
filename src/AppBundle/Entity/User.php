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

    /** @var  string|null $firstName */
    private $firstName;

    /** @var  string $lastName */
    private $lastName;

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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName(string $firstName)
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

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName(string $lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName() : ?string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getFullName() : string
    {
        return "{$this->firstName} {$this->lastName}";
    }
}
