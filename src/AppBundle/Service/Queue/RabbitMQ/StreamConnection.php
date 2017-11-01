<?php

namespace AppBundle\Service\Queue\RabbitMQ;

use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * Class StreamConnection
 * @package AppBundle\Service\Queue\RabbitMQ
 */
class StreamConnection
{
    /** @var null|AMQPStreamConnection $insance */
    private static $insance = null;


    private function __construct()  {}
    private function __clone() {}

    /**
     * @return AMQPStreamConnection
     */
    public static function getInstance() : AMQPStreamConnection
    {
        if (is_null(self::$insance)) {
            self::$insance = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        }

        return self::$insance;
    }
}
