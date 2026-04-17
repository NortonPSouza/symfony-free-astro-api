<?php

namespace App\Infra\Adapters\Queue;

use App\App\Contracts\Gateway\QueueInterface;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

class RabbitQueue implements QueueInterface
{
    private ?AMQPStreamConnection $connection = null;
    private ?AMQPChannel $channel = null;

    /**
     * @throws \Exception
     */
    private function getConnection(): AMQPStreamConnection
    {
        if ($this->connection === null || !$this->connection->isConnected()) {
            $this->connection = new AMQPStreamConnection(
                host: $_ENV['AMQP_HOST'],
                port: $_ENV['AMQP_PORT'],
                user: $_ENV['AMQP_USERNAME'],
                password: $_ENV['AMQP_PASSWORD']
            );
            $this->channel = null;
        }
        return $this->connection;
    }

    /**
     * @throws \Exception
     */
    private function getChannel(): AMQPChannel
    {
        if ($this->channel === null || !$this->channel->is_open()) {
            $this->channel = $this->getConnection()->channel();
        }
        return $this->channel;
    }

    /**
     * @throws \Exception
     */
    public function sender(string $message, string $queue, string $routeKey, array $header): void
    {
        $channel = $this->getChannel();
        $channel->exchange_declare($queue, type: 'direct', durable: true, auto_delete: false);
        $channel->queue_declare($queue, durable: true, auto_delete: false);
        $channel->queue_bind($queue, $queue, $routeKey);
        $amqpMessage = new AMQPMessage($message, [
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
            'application_headers' => new AMQPTable($header),
        ]);
        $channel->basic_publish($amqpMessage, exchange: $queue, routing_key: $routeKey);
    }

    /**
     * @throws \Exception
     */
    public function consume(string $queue, callable $callback): void
    {
        $channel = $this->getChannel();
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

    /**
     * @throws \Exception
     */
    public function __destruct()
    {
        $this->channel?->close();
        $this->connection?->close();
    }
}