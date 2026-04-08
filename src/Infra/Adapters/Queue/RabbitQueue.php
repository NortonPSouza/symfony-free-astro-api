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
    private function connection(): AMQPStreamConnection
    {
        return new AMQPStreamConnection(
            host: $_ENV['AMQP_HOST'],
            port: $_ENV['AMQP_PORT'],
            user: $_ENV['AMQP_USERNAME'],
            password: $_ENV['AMQP_PASSWORD']
        );
    }

    /**
     * @throws \Exception
     */
    protected function sender(string $message, string $queue, string $routeKey, array $header): void
    {
        $connection = $this->connection();
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

    /**
     * @throws \Exception
     */
    protected function receiver(string $queue, callable $callback): void
    {
        $connection = $this->connection();
        $channel = $connection->channel();
        $channel->exchange_declare($queue, type: 'direct', durable: true, auto_delete: false);
        $channel->queue_declare($queue, durable: true, auto_delete: false);
        $channel->queue_bind($queue, $queue, $queue);
        $channel->basic_qos(prefetch_size: 0, prefetch_count: 1, a_global: false);
        $channel->basic_consume(
            queue: $queue,
            callback: function (AMQPMessage $message) use ($callback) {
                try {
                    $payload = json_decode($message->getBody(), true);
                    $callback($payload);
                    $message->ack();
                } catch (\Throwable $e) {
                    fwrite(STDERR, 'Queue error: ' . $e->getMessage() . PHP_EOL);
                    $message->nack();
                }
            }
        );
        while ($channel->is_consuming()) {
            $channel->wait();
        }
    }
}