<?php

namespace AppBundle\EventListener\User;

use AppBundle\Contract\Queue\IMailPublisher;
use AppBundle\Event\User\UserRegisteredEvent;

/**
 * Class UserRegisterListener
 * @package AppBundle\EventListener\User
 */
class UserRegisterListener
{
    protected $mailPublisher;

    public function __construct(
        IMailPublisher $mailPublisher
    )
    {
        $this->mailPublisher = $mailPublisher;
    }

    /**
     * @param UserRegisteredEvent $event
     */
    public function onUserRegistered(UserRegisteredEvent $event) : void
    {
        $user = $event->getUser();
        $this->mailPublisher->publish('userRegisteredMail', [
            'email' => $user->getEmail(),
            'login' => $user->getLogin()
        ]);
    }
}
