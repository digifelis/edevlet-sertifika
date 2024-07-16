<?php
/* create rabbitmq consumer service */
namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQConsumerService
{
    private $connection;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST'),
            env('RABBITMQ_PORT'),
            env('RABBITMQ_LOGIN'),
            env('RABBITMQ_PASSWORD'),
        );
    }

    public function consumeMessages($queueName, $callback)
    {
        $channel = $this->connection->channel();
        $channel->queue_declare($queueName, false, true, false, false);

        $channel->basic_consume(
            $queueName,
            '',
            false,
            true,
            false,
            false,
            $callback
        );

        while (count($channel->callbacks)) {
            $channel->wait();
        }

        $channel->close();
        $this->connection->close();
    }
}
