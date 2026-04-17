<?php

namespace App\Infra\Adapters\Repository;

use App\App\Contracts\Repository\ReportLogRepositoryInterface;
use App\Domain\Entity\Report;
use App\Domain\Exceptions\RepositoryException;
use App\Infra\Adapters\Document\ReportLog;
use Doctrine\ODM\MongoDB\DocumentManager;

readonly class ReportLogRepository implements ReportLogRepositoryInterface
{
    public function __construct(
        private DocumentManager $documentManager
    ) {
    }

    public function save(Report $report): void
    {
        try {
            $reportLog = new ReportLog();
            $reportLog
                ->setProcessId($report->getProcessId())
                ->setUserId($report->getUserId())
                ->setStatus($report->getStatus())
                ->setMonth($report->getMonth())
                ->setYear($report->getYear());
            $this->documentManager->persist($reportLog);
            $this->documentManager->flush();
        } catch (\Exception|\Throwable $exception) {
            throw new RepositoryException($exception->getMessage());
        }
    }
}
