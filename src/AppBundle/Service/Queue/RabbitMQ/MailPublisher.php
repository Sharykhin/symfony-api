<?php

namespace AppBundle\Service\Queue\RabbitMQ;

use AppBundle\Contract\Queue\IMailPublisher;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class MailPublisher
 * @package AppBundle\Service\Queue\RabbitMQ
 */
class MailPublisher implements IMailPublisher
{
    /** @var \PhpAmqpLib\Channel\AMQPChannel $channel */
    protected $channel;

    const CHANNEL_NAME = 'mail';

    /**
     * MailPublisher constructor.
     */
    public function __construct()
    {
        $this->channel = StreamConnection::getInstance()->channel();
        $this->channel->queue_declare(self::CHANNEL_NAME, false, false, false, false);
    }

    /**
     * @param string $mail
     * @param array $payload
     */
    public function publish(string $mail, array $payload = []) : void
    {
        $msg = new AMQPMessage(json_encode([
            'mail' => $mail,
            'payload' => $payload
        ]));
        $this->channel->basic_publish($msg, '', self::CHANNEL_NAME);
        $this->channel->close();
        StreamConnection::getInstance()->close();
    }
}