<?php

namespace App\Infra\Ports\Command;

use App\App\Contracts\Gateway\QueueInterface;

readonly class GenerateReportConsumer
{
    public function __construct(
        private QueueInterface $queue
    ) {
    }

    /**
     * @throws \Exception
     */
    public function listen(callable $callback): void
    {
        $this->queue->consume('horoscope.monthly.report', function (array $payload) use ($callback) {
            $callback($payload);
        });
    }
}
