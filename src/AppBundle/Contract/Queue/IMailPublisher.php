<?php

namespace AppBundle\Contract\Queue;

/**
 * Interface IMailPublisher
 * @package AppBundle\Contract\Queue
 */
interface IMailPublisher
{
    /**
     * @param string $mail
     * @param array $payload
     */
    public function publish(string $mail, array $payload = []) : void;
}
