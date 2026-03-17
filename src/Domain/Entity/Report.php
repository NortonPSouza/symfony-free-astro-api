<?php

namespace App\Domain\Entity;


use App\App\UseCase\Report\Create\Input\CreateReportInput;
use App\Domain\Types\ReportStatus;

class Report
{

    public function __construct(
        private int $userId,
        private int $month,
        private int $year,
        private int $status
    )
    {
    }

    static public function create(CreateReportInput $input): Report
    {
        return new Report(
            $input->getUserId(),
            $input->getMonth(),
            $input->getYear(),
            ReportStatus::PENDING->getStatus()
        );
    }

    public function getUserId(): int
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

}