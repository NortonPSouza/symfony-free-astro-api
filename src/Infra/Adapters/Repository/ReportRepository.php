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
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\Exception\ORMException;

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

    /**
     * @throws RepositoryException
     * @throws NotFoundException
     * @throws Exception|ORMException
     */
    public function updateStatus(string $reportId, ReportStatusType $status): Report
    {
        try {
            $entityManager = $this->connection->getEntityManager();
            $this->connection->begin();
            $reportStatusMapper = $entityManager->getReference(ReportStatus::class, $status->getStatus());
            $reportMapper = $entityManager->find(
                ReportMapper::class,
                $reportId,
                LockMode::PESSIMISTIC_WRITE
            );
        } catch (\Exception|Exception|ORMException $exception) {
            $this->connection->rollBack();
            throw new RepositoryException($exception->getMessage());
        }
        if (!$reportMapper) {
            $this->connection->rollBack();
            throw new NotFoundException("Report not found");
        }
        if ($status === ReportStatusType::PROCESSING
            && $reportMapper->getStatus()->getId() !== ReportStatusType::PENDING->value
        ) {
            $this->connection->rollback();
            throw new RepositoryException("Invalid state transition");
        }
        try {
            $reportMapper->setStatus($reportStatusMapper);
            if ($status === ReportStatusType::COMPLETED) {
                $reportMapper->setCompletedAt(new \DateTime());
            }
            $this->connection->commit();
            return Report::fromPrimitives(
                $reportMapper->getUser()->getId(),
                $reportMapper->getMonth(),
                $reportMapper->getYear(),
                $reportMapper->getStatus()->getId(),
                $reportMapper->getId(),
                $reportMapper->getRequestedAt()
            );
        } catch (\Exception|Exception $exception) {
            $this->connection->rollBack();
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
