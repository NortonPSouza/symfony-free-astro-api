<?php

namespace App\App\UseCase\Report\Generate;

use App\App\Contracts\Gateway\PdfGeneratorInterface;
use App\App\Contracts\Repository\ReportLogRepositoryInterface;
use App\App\Contracts\Repository\ReportRepositoryInterface;
use App\App\Contracts\Repository\UserRepositoryInterface;
use App\App\UseCase\Report\Generate\Input\GenerateReportInput;
use App\App\UseCase\Report\Generate\Output\GenerateReportOutput;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\Exceptions\PdfGenerationException;
use App\Domain\Exceptions\RepositoryException;
use App\Domain\Types\ReportStatus;

readonly class GenerateReportUseCase
{
    public function __construct(
        private ReportRepositoryInterface $reportRepository,
        private ReportLogRepositoryInterface $reportLogRepository,
        private UserRepositoryInterface $userRepository,
        private PdfGeneratorInterface $pdfGenerator
    ) {}

    public function execute(GenerateReportInput $input): GenerateReportOutput
    {
        try {
            $this->reportRepository->updateStatus($input->getProcessId(), ReportStatus::PROCESSING);
            sleep(120);
            $report = $this->reportRepository->findById($input->getProcessId());
            $user = $this->userRepository->find($report->getUserId());
            $this->pdfGenerator->generate($user, $report);
            $this->reportRepository->updateStatus($input->getProcessId(), ReportStatus::COMPLETED);
            $this->reportLogRepository->save($report);
            return GenerateReportOutput::success([]);
        } catch (RepositoryException|NotFoundException|PdfGenerationException $exception) {
            $this->reportRepository->updateStatus($input->getProcessId(), ReportStatus::FAILURE);
            return GenerateReportOutput::failure($exception->getStatusCode(), $exception->getData());
        }
    }
}