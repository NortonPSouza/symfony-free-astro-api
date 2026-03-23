<?php

namespace App\App\UseCase\Report\Generate;

use App\App\Contracts\Repository\ReportRepositoryInterface;
use App\App\UseCase\Report\Generate\Input\GenerateReportInput;
use App\App\UseCase\Report\Generate\Output\GenerateReportOutput;
use App\Domain\Exceptions\RepositoryException;
use App\Domain\Types\ReportStatus;

readonly class GenerateReportUseCase
{
    public function __construct(
        private ReportRepositoryInterface $reportRepository
    )
    {
    }

    public function execute(GenerateReportInput $input): GenerateReportOutput
    {
        try {
            $this->reportRepository->updateStatus($input->getProcessId(), ReportStatus::PROCESSING);
            // TODO: generate pdf
            sleep(10); //
            $this->reportRepository->updateStatus($input->getProcessId(), ReportStatus::COMPLETED);
            sleep(10);
            // TODO: insert mongo log
            return new GenerateReportOutput();
        } catch (RepositoryException) {
            return new GenerateReportOutput();
        }
    }
}