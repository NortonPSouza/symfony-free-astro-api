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
        $channel->exchange_declare($queue, type: 'direct', durable: true, auto_delete: false);
        $channel->queue_declare($queue, durable: true, auto_delete: false);
        $channel->queue_bind($queue, $queue, $routeKey);
        $amqpMessage = new AMQPMessage($message, [
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
            'application_headers' => new AMQPTable($header),
        ]);
        $channel->basic_publish($amqpMessage, exchange: $queue, routing_key: $routeKey);
        $channel->close();
        $connection->close();
    }
}