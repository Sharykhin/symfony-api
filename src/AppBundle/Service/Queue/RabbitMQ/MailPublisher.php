<?php

namespace AppBundle\Service\Queue\RabbitMQ;

use AppBundle\Contract\Queue\IMailPublisher;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class MailPublisher
 * @package AppBundle\Service\Queue\RabbitMQ
 */
class MailPublisher implements IMailPublisher
{
    const CHANNEL_NAME = 'mail';

    /** @var \PhpAmqpLib\Channel\AMQPChannel $channel */
    protected $channel = null;

    /** @var null|\PhpAmqpLib\Connection\AMQPStreamConnection $connection */
    protected $connection;

    /** @var ContainerInterface $container */
    protected $container;

    /**
     * MailPublisher constructor.
     * @param StreamConnection $connection
     * @param ContainerInterface $container
     */
    public function __construct(
        StreamConnection $connection,
        ContainerInterface $container
    )
    {
        $this->connection = $connection->getConnection();
        $this->container = $container;
        if (!is_null($this->connection)) {
            $this->channel = $this->connection->channel();
            $this->channel->queue_declare(self::CHANNEL_NAME, false, false, false, false);
        }
    }

    /**
     * @param string $mail
     * @param array $payload
     */
    public function publish(string $mail, array $payload = []) : void
    {
        if (is_null($this->channel)) {
            /** @var LoggerInterface $logger */
            $logger = $this->container->get('monolog.logger.queue');
            $logger->error('Failed publish', [
                'mail' => $mail,
                'payload' => $payload
            ]);
            return;
        }

        $msg = new AMQPMessage(json_encode([
            'mail' => $mail,
            'payload' => $payload
        ]));
        $this->channel->basic_publish($msg, '', self::CHANNEL_NAME);
        $this->channel->close();
        $this->connection->close();
    }
}
