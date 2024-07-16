<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService
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

    public function publishMessage($message, $queueName)
    {
        $channel = $this->connection->channel();
       // $channel->queue_declare($queueName, false, true, false, false);

        $channel->queue_declare(
            $queue = $queueName,
            $passive = false,
            $durable = true,
            $exclusive = false,
            $auto_delete = false,
            $nowait = false,
            $arguments = null,
            $ticket = null
        );

        $message = new AMQPMessage(
            json_encode($message, JSON_UNESCAPED_SLASHES),
            array('delivery_mode' => 2) # make message persistent
        );
        $channel->basic_publish($message, '', $queueName);

        $channel->close();
        $this->connection->close();
    }
}
