<?php

namespace App\Infra\Adapters\Queue;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

abstract class RabbitQueue
{


    /**
     * @throws \Exception
     */
    protected function sender(string $message, string $queue, string $routeKey, array $header): void
    {
        $connection = new AMQPStreamConnection(
            host: $_ENV['AMQP_HOST'],
            port: $_ENV['AMQP_PORT'],
            user: $_ENV['AMQP_USERNAME'],
            password: $_ENV['AMQP_PASSWORD']
        );
        $channel = $connection->channel();
        $channel->queue_declare($queue, auto_delete: false);
        $amqpMessage = new AMQPMessage($message, [
            'application_headers' => new AMQPTable($header),
        ]);
        // TODO: search about field exchange
        $channel->basic_publish($amqpMessage, exchange: $queue, routing_key: $routeKey);
        $channel->close();
        $connection->close();
    }
}