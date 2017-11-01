<?php

namespace AppBundle\Service\Queue\RabbitMQ;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class StreamConnection
 * @package AppBundle\Service\Queue\RabbitMQ
 */
class StreamConnection
{

    /** @var AMQPStreamConnection $connection */
    protected $connection;

    /**
     * StreamConnection constructor.
     * @param ContainerInterface $container
     */
    public function __construct(
        ContainerInterface $container
    )  {
        $this->connection = new AMQPStreamConnection(
            $container->getParameter('rabbitmq_host'),
            $container->getParameter('rabbitmq_port'),
            $container->getParameter('rabbitmq_user'),
            $container->getParameter('rabbitmq_pass')
        );
    }

    /**
     * @return null|AMQPStreamConnection
     */
    public function getConnection() : AMQPStreamConnection
    {
        return $this->connection;
    }
}
