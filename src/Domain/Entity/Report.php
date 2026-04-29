<?php

namespace App\Domain\Entity;


readonly class Report
{

    public function __construct(
        private string $userId,
        private int $month,
        private int $year,
        private int $status,
        private ?string $processId,
        private ?\DateTime $requestedAt
    )
    {
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

    public function getRequestedAt(): ?\DateTime
    {
        return $this->requestedAt;
    }

    public static function fromPrimitives(
        string $userId,
        int $month,
        int $year,
        int $status,
        ?string $processId = null,
        ?\DateTime $requestedAt = null
    ): Report
    {
        return new Report($userId, $month, $year, $status, $processId, $requestedAt);
    }
}