<?php

namespace App\Domain\Builder;

use App\Domain\Entity\Report;

class ReportBuilder
{
    private string $userId;
    private int $month;
    private int $year;
    private int $status = 1;
    private ?string $processId = null;
    private ?\DateTime $requestedAt = null;

    public function withUserId(string $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function withMonth(int $month): self
    {
        $this->month = $month;
        return $this;
    }

    public function withYear(int $year): self
    {
        $this->year = $year;
        return $this;
    }

    public function withStatus(int $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function withProcessId(?string $processId): self
    {
        $this->processId = $processId;
        return $this;
    }

    public function withRequestedAt(?\DateTime $requestedAt): self
    {
        $this->requestedAt = $requestedAt;
        return $this;
    }

    public function build(): Report
    {
        return new Report(
            $this->userId,
            $this->month,
            $this->year,
            $this->status,
            $this->processId,
            $this->requestedAt
        );
    }
}
