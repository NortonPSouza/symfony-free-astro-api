<?php

namespace App\Infra\Adapters\Repository;

use App\App\Contracts\Repository\ReportRepositoryInterface;
use App\Domain\Entity\Report;
use App\Domain\Exceptions\NotFoundException;
use App\Infra\Adapters\Database\ConnectionDoctrine;
use App\Infra\Adapters\Mappers\Report as ReportMapper;
use App\Domain\Exceptions\RepositoryException;
use App\Infra\Adapters\Mappers\ReportStatus;
use App\Infra\Adapters\Mappers\User;
use Doctrine\ORM\Exception\ORMException;

readonly class ReportRepository implements ReportRepositoryInterface
{

    public function __construct(
        private ConnectionDoctrine $connection
    )
    {
    }

    /**
     * @throws RepositoryException
     */
    public function create(Report $report): Report
    {
        try {
            $entityManager = $this->connection->getEntityManager();
            $reportStatusMapper = $entityManager->getRepository(ReportStatus::class)
                ->find($report->getStatus());
            $userMapper = $entityManager->getReference(User::class, $report->getUserId());
            if (!$userMapper) {
                throw new NotFoundException("user not found");
            }
            $reportMapper = new ReportMapper();
            $reportMapper
                ->setUser($userMapper)
                ->setMonth($report->getMonth())
                ->setYear($report->getYear())
                ->setStatus($reportStatusMapper);
            $entityManager->persist($reportMapper);
            $entityManager->flush();
            $report->setProcessId($reportMapper->getReportId());
            return $report;
        } catch (ORMException|NotFoundException $exception) {
            throw new RepositoryException($exception->getMessage());
        }
    }
}