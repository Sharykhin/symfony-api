<?php

namespace AppBundle\Event\User;

use AppBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class UserRegisteredEvent
 * @package AppBundle\Event\User
 */
class UserRegisteredEvent extends Event
{
    const NAME = 'user.registered';

    /** @var User $user */
    protected $user;

    /**
     * UserRegisteredEvent constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser() : User
    {
        return $this->user;
    }
}
