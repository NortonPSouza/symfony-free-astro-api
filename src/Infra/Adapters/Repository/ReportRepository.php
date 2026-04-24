<?php

namespace App\Infra\Adapters\Repository;

use App\App\Contracts\Repository\ReportRepositoryInterface;
use App\Domain\Entity\Report;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\Exceptions\RepositoryException;
use App\Domain\Types\ReportStatus as ReportStatusType;
use App\Infra\Adapters\Database\ConnectionDoctrine;
use App\Infra\Mappers\Report as ReportMapper;
use App\Infra\Mappers\ReportStatus;
use App\Infra\Mappers\User;

readonly class ReportRepository implements ReportRepositoryInterface
{
    public function __construct(
        private ConnectionDoctrine $connection
    ) {
    }

    public function create(Report $report): Report
    {
        try {
            $entityManager = $this->connection->getEntityManager();
            $reportStatusMapper = $entityManager->getRepository(ReportStatus::class)
                ->find($report->getStatus());
            $userMapper = $entityManager->getReference(User::class, $report->getUserId());
            $reportMapper = new ReportMapper();
            $reportMapper
                ->setUser($userMapper)
                ->setMonth($report->getMonth())
                ->setYear($report->getYear())
                ->setStatus($reportStatusMapper);
            $entityManager->persist($reportMapper);
            $entityManager->flush();
            return Report::fromPrimitives(
                $report->getUserId(),
                $reportMapper->getMonth(),
                $reportMapper->getYear(),
                $reportMapper->getStatus()->getId(),
                $reportMapper->getId(),
                $reportMapper->getRequestedAt()
            );
        } catch (\Exception $exception) {
            throw new RepositoryException($exception->getMessage());
        }
    }

    public function updateStatus(string $reportId, ReportStatusType $status): Report
    {
        try {
            $entityManager = $this->connection->getEntityManager();
            $reportStatusMapper = $entityManager->getReference(ReportStatus::class, $status->getStatus());
            $reportMapper = $entityManager->getRepository(ReportMapper::class)->find($reportId);
        } catch (\Exception $exception) {
            throw new RepositoryException($exception->getMessage());
        }
        if (!$reportMapper) {
            throw new NotFoundException("Report not found");
        }
        try {
            $reportMapper->setStatus($reportStatusMapper);
            if ($status === ReportStatusType::COMPLETED) {
                $reportMapper->setCompletedAt(new \DateTime());
            }
            $entityManager->persist($reportMapper);
            $entityManager->flush();
            return Report::fromPrimitives(
                $reportMapper->getUser()->getId(),
                $reportMapper->getMonth(),
                $reportMapper->getYear(),
                $reportMapper->getStatus()->getId(),
                $reportMapper->getId(),
                $reportMapper->getRequestedAt()
            );
        } catch (\Exception $exception) {
            throw new RepositoryException($exception->getMessage());
        }
    }

    public function findById(string $reportId): Report
    {
        try {
            $entityManager = $this->connection->getEntityManager();
            $reportMapper = $entityManager->getRepository(ReportMapper::class)->find($reportId);
        } catch (\Exception $exception) {
            throw new RepositoryException($exception->getMessage());
        }
        if (!$reportMapper) {
            throw new NotFoundException("Report not found");
        }
        return Report::fromPrimitives(
            $reportMapper->getUser()->getId(),
            $reportMapper->getMonth(),
            $reportMapper->getYear(),
            $reportMapper->getStatus()->getId(),
            $reportMapper->getId(),
            $reportMapper->getRequestedAt()
        );
    }
}
