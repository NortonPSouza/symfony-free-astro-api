<?php

namespace App\App\Contracts\Gateway;

interface QueueInterface
{
    /**
     * @param string $message
     * @param string $queue
     * @param string $routeKey
     * @param array $header
     * @return void
     */
    public function sender(string $message, string $queue, string $routeKey, array $header): void;

    /**
     * @param string $queue
     * @param callable $callback
     * @return void
     */
    public function consume(string $queue, callable $callback): void;
}
