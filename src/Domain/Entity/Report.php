<?php

namespace App\Domain\Entity;


class Report
{

    public function __construct(
        private readonly string $userId,
        private readonly int $month,
        private readonly int $year,
        private readonly int $status,
        private readonly ?string $processId,
        private readonly ?\DateTime $requestedAt
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