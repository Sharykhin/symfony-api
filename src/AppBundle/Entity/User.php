<?php

namespace AppBundle\Entity;

use AppBundle\Contract\Entity\IAdvancedUser;

/**
 * Class User
 * @package AppBundle\Entity
 */
class User implements IAdvancedUser
{
    /** @var  $id */
    private $id;

    /** @var string|null $firstName */
    private $firstName;

    /** @var string|null $lastName */
    private $lastName;

    /** @var string $login */
    private $login;

    /** @var string $password */
    private $password;

    /** @var string $email */
    private $email;

    /** @var  $roles */
    private $role;

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
     * @param string|null $firstName
     *
     * @return User
     */
    public function setFirstName(?string $firstName)
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
     * @param string|null $lastName
     *
     * @return User
     */
    public function setLastName(?string $lastName)
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
    public function getFullName() : ?string
    {
        if (is_null($this->firstName) && is_null($this->lastName)) {
            return null;
        }
        return "{$this->firstName} {$this->lastName}";
    }

    /**
     * Set login
     *
     * @param string $login
     *
     * @return User
     */
    public function setLogin(string $login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        return [$this->role];
    }

    /**
     * @return null
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->login;
    }

    /**
     *
     */
    public function eraseCredentials()
    {
        return null;
    }

    /**
     * Set role
     *
     * @param string $role
     *
     * @return User
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail() : ?string
    {
        return $this->email;
    }
}
