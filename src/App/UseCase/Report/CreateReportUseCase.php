<?php

namespace App\App\UseCase\Report;

use App\App\Contracts\Repository\ReportRepositoryInterface;
use App\App\UseCase\Report\Create\Input\CreateReportInput;
use App\App\UseCase\Report\Create\Output\CreateReportOutput;
use App\Domain\Entity\Report;
use App\Domain\Exceptions\NotFoundException;
use Doctrine\ORM\Exception\ORMException;

readonly class CreateReportUseCase
{

    public function __construct(
        private ReportRepositoryInterface $reportRepository
    )
    {
    }

    public function execute(CreateReportInput $input): CreateReportOutput
    {
        try {
            $report = Report::create($input);
            $created = $this->reportRepository->create($report);
            return CreateReportOutput::success($created);
        } catch (ORMException|NotFoundException $exception) {
            return CreateReportOutput::failure($exception->getCode(), $exception->getData());
        }
    }
}