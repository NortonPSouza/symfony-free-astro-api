<?php

namespace App\Infra\Adapters\Repository;

use App\App\Contracts\Repository\ReportRepositoryInterface;
use App\Domain\Entity\Report;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\Types\ReportStatus as ReportStatusType;
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
            $report->setProcessId($reportMapper->getId())
                ->setRequestedAt($reportMapper->getRequestedAt());
            return $report;
        } catch (ORMException|NotFoundException $exception) {
            throw new RepositoryException($exception->getMessage());
        }
    }


    public function updateStatus(string $reportId, ReportStatusType $status): Report
    {
        try {
            $entityManager = $this->connection->getEntityManager();
            $reportStatusMapper = $entityManager->getReference(ReportStatus::class, $status->getStatus());
            $reportMapper = $entityManager->getRepository(ReportMapper::class)->find($reportId);
            $reportMapper
                ->setStatus($reportStatusMapper);
            if ($status === ReportStatusType::COMPLETED) {
                $reportMapper->setCompletedAt(new \DateTime());
            }
            $entityManager->persist($reportMapper);
            $entityManager->flush();
            return $reportMapper->toDomain();
        } catch (ORMException $exception) {
            throw new RepositoryException($exception->getMessage());
        }
    }

    public function findById(string $reportId): Report
    {
        try {
            $entityManager = $this->connection->getEntityManager();
            $reportMapper = $entityManager->getRepository(ReportMapper::class)->find($reportId);
            if (!$reportMapper) {
                throw new NotFoundException("report not found");
            }
            return $reportMapper->toDomain();
        } catch (NotFoundException $exception) {
            throw new RepositoryException($exception->getMessage());
        }
    }
}