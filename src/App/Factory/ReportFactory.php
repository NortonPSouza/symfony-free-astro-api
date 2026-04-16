<?php

namespace App\App\Factory;

use App\App\UseCase\Report\Create\Input\CreateReportInput;
use App\Domain\Entity\Report;
use App\Domain\Types\ReportStatus;

abstract class ReportFactory
{
    static public function fromInput(CreateReportInput $input): Report
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
}