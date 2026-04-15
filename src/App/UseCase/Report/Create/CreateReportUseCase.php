<?php

namespace App\App\UseCase\Report\Create;

use App\App\Contracts\Gateway\EventProcessReportInterface;
use App\App\Contracts\Repository\ReportRepositoryInterface;
use App\App\UseCase\Report\Create\Input\CreateReportInput;
use App\App\UseCase\Report\Create\Output\CreateReportOutput;
use App\Domain\Entity\Report;
use App\Domain\Exceptions\EventException;
use App\Domain\Exceptions\NotFoundException;
use Doctrine\ORM\Exception\ORMException;

readonly class CreateReportUseCase
{

    public function __construct(
        private ReportRepositoryInterface $reportRepository,
        private EventProcessReportInterface $eventProcessReport
    )
    {
    }

    public function execute(CreateReportInput $input): CreateReportOutput
    {
        try {
            $report = Report::create($input);
            $created = $this->reportRepository->create($report);
            $this->eventProcessReport->execute($created);
            return CreateReportOutput::success(['id' => $created->getProcessId()]);
        } catch (ORMException|NotFoundException|EventException $exception) {
            return CreateReportOutput::failure($exception->getStatusCode(), $exception->getData());
        }
    }
}