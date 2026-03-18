<?php

namespace App\Infra\Adapters\Queue;

use App\App\Contracts\Queue\QueueInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

class RabbitQueue implements QueueInterface
{

    /**
     * @throws \Exception
     */
    public function sender(string $message, string $queue, string $routeKey, array $header): void
    {
        $connection = new AMQPStreamConnection(
            host: $_ENV['AMQP_HOST'],
            port: $_ENV['AMQP_PORT'],
            user: $_ENV['AMQP_USERNAME'],
            password: $_ENV['AMQP_PASSWORD']
        );
        $channel = $connection->channel();
        $channel->queue_declare($queue, durable: true, auto_delete: false);
        $amqpMessage = new AMQPMessage($message, [
            'application_headers' => new AMQPTable($header),
        ]);
        $channel->basic_publish($amqpMessage, exchange: $queue, routing_key: $routeKey);
        $channel->close();
        $connection->close();
    }
}