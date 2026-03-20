<?php

namespace App\Domain\Entity;


use App\App\UseCase\Report\Create\Input\CreateReportInput;
use App\Domain\Types\ReportStatus;

class Report
{

    public function __construct(
        private readonly string $userId,
        private readonly int $month,
        private readonly int $year,
        private readonly int $status,
        private ?string $processId,
        private ?\DateTime $requestedAt
    )
    {
    }

    static public function create(CreateReportInput $input): Report
    {
        return new Report(
            $input->getUserId(),
            $input->getMonth(),
            $input->getYear(),
            ReportStatus::PENDING->getStatus(),
            null,
            null
        );
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getMonth(): int
    {
        return $this->month;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getProcessId(): ?string
    {
        return $this->processId;
    }

    public function setProcessId(?string $processId): Report
    {
        $this->processId = $processId;
        return $this;
    }

    public function getRequestedAt(): ?\dateTime
    {
        return $this->requestedAt;
    }

    public function setRequestedAt(?\dateTime $requestedAt): Report
    {
        $this->requestedAt = $requestedAt;
        return $this;
    }

}