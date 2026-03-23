<?php

namespace App\Infra\Command;

use App\Infra\Adapters\Queue\RabbitQueue;

final class GenerateReportConsumer extends RabbitQueue
{
    /**
     * @throws \Exception
     */
    public function listen(callable $callback): void
    {
        $this->receiver('horoscope.monthly.report', function (array $payload) use ($callback){
            $callback($payload);
        });
    }
}