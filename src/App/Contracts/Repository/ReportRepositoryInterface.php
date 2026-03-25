<?php

namespace App\App\Contracts\Repository;

use App\Domain\Entity\Report;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\Exceptions\RepositoryException;
use App\Domain\Types\ReportStatus;
use Doctrine\ORM\Exception\ORMException;

interface ReportRepositoryInterface
{

    /**
     * @param Report $report
     * @return Report
     * @throws ORMException
     * @throws NotFoundException
     */
    public function create(Report $report): Report;

    /**
     * @param string $reportId
     * @param ReportStatus $status
     * @return Report
     * @throws RepositoryException
     */
    public function updateStatus(string $reportId, ReportStatus $status): Report;

    /**
     * @param string $reportId
     * @return Report
     * @throws RepositoryException
     */
    public function findById(string $reportId): Report;
}