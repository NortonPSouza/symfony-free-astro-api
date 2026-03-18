<?php

namespace App\Infra\Adapters\Gateway;

use App\App\Contracts\Gateway\EventProcessReportInterface;
use App\Domain\Entity\Report;
use App\Domain\Exceptions\EventException;
use App\Infra\Adapters\Queue\RabbitQueue;

class EventProcessReport extends RabbitQueue implements EventProcessReportInterface
{

    public function execute(Report $report): void
    {
        try {
            $payload = [];
            $this->sender(
                message: json_encode($payload),
                queue: "process_report",
                routeKey: "process_report",
                header: ['x-queue-type' => 'quorum']
            );
        } catch (\Exception $exception) {
            throw new EventException($exception->getMessage());
        }

    }
}