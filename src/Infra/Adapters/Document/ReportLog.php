<?php

namespace App\Infra\Adapters\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document(collection: 'report_logs')]
class ReportLog
{
    #[ODM\Id]
    private ?string $id = null;

    #[ODM\Field(type: 'string')]
    private string $processId;

    #[ODM\Field(type: 'string')]
    private string $userId;

    #[ODM\Field(type: 'int')]
    private int $month;

    #[ODM\Field(type: 'int')]
    private int $year;

    #[ODM\Field(type: 'int')]
    private int $status;

    #[ODM\Field(type: 'date')]
    private \DateTime $processedAt;


    public function __construct()
    {
        $this->processedAt = new \DateTime();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): ReportLog
    {
        $this->id = $id;
        return $this;
    }

    public function getProcessId(): string
    {
        return $this->processId;
    }

    public function setProcessId(string $processId): ReportLog
    {
        $this->processId = $processId;
        return $this;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): ReportLog
    {
        $this->userId = $userId;
        return $this;
    }

    public function getMonth(): int
    {
        return $this->month;
    }

    public function setMonth(int $month): ReportLog
    {
        $this->month = $month;
        return $this;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year): ReportLog
    {
        $this->year = $year;
        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): ReportLog
    {
        $this->status = $status;
        return $this;
    }

    public function getProcessedAt(): \DateTime
    {
        return $this->processedAt;
    }

    public function setProcessedAt(\DateTime $processedAt): ReportLog
    {
        $this->processedAt = $processedAt;
        return $this;
    }

}
