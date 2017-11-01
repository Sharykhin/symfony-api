<?php

namespace AppBundle\EntityPropertyEventRegistry\User;

use AppBundle\Contract\Queue\IMailPublisher;
use AppBundle\Entity\User;

/**
 * Class OnFirstNameChanged
 * @package AppBundle\EntityPropertyEventRegistry\User
 */
class OnFirstNameChanged
{
    const FIRST_NAME_CHANGED_TYPE = 'firstNameChanged';

    /** @var IMailPublisher $mailPublisher */
    protected $mailPublisher;

    /**
     * OnFirstNameChanged constructor.
     * @param IMailPublisher $mailPublisher
     */
    public function __construct(
        IMailPublisher $mailPublisher
    )
    {
        $this->mailPublisher = $mailPublisher;
    }

    /**
     * @param User $user
     * @param string $oldValue
     * @param string $newValue
     */
    public function execute(User $user, string $oldValue, string $newValue) : void
    {
        $this->mailPublisher->publish(self::FIRST_NAME_CHANGED_TYPE, [
            'email' => 'chapal@inbox.ru',
            'first_name' => $newValue,
            'old_name' => $oldValue,
        ]);
    }
}
