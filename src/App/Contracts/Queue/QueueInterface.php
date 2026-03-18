<?php

namespace App\App\Contracts\Queue;

interface QueueInterface
{

    // TODO - review parameters with cauã
    public function sender(string $message, string $queue, string $routeKey, array $header): void;
}