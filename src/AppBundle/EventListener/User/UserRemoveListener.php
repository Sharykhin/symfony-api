<?php

namespace AppBundle\EventListener\User;

use AppBundle\Contract\Queue\IMailPublisher;
use AppBundle\Event\User\UserRemovedEvent;

/**
 * Class UserRemoveListener
 * @package AppBundle\EventListener\User
 */
class UserRemoveListener
{
    protected $mailPublisher;

    public function __construct(
        IMailPublisher $mailPublisher
    )
    {
        $this->mailPublisher = $mailPublisher;
    }

    /**
     * @param UserRemovedEvent $event
     */
    public function onUserRemoved(UserRemovedEvent $event) : void
    {
        $user = $event->getUser();
        $this->mailPublisher->publish('userRemovedMail', [
            'email' => $user->getEmail(),
            'login' => $user->getLogin()
        ]);
    }
}
