<?php

namespace AppBundle\Contract\Queue;

/**
 * Interface IMailPublisher
 * @package AppBundle\Contract\Queue
 */
interface IMailPublisher
{
    const FIRST_NAME_CHANGED_TYPE = 'firstNameChanged';

    /**
     * @param string $mail
     * @param array $payload
     */
    public function publish(string $mail, array $payload = []) : void;
}
