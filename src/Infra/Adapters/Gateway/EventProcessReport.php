<?php

namespace App\Infra\Adapters\Gateway;

use App\App\Contracts\Gateway\EventProcessReportInterface;
use App\App\Contracts\Gateway\QueueInterface;
use App\Domain\Entity\Report;
use App\Domain\Exceptions\EventException;

readonly class EventProcessReport implements EventProcessReportInterface
{
    public function __construct(
        private QueueInterface $queue
    ) {
    }

    public function execute(Report $report): void
    {
        try {
            $payload = [
                "process_id" => $report->getProcessId(),
                "user_id" => $report->getUserId(),
                "month" => $report->getMonth(),
                "year" => $report->getYear(),
                "status" => $report->getStatus(),
                "requested_at" => $report->getRequestedAt()->format('Y-m-d\TH:i:sP'),
                "completed_at" => null
            ];
            $this->queue->sender(
                message: json_encode($payload),
                queue: "horoscope.monthly.report",
                routeKey: "horoscope.monthly.report",
                header: ['x-queue-type' => 'quorum']
            );
        } catch (\Exception $exception) {
            throw new EventException($exception->getMessage());
        }
    }
}
