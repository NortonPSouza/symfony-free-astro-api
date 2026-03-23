<?php

namespace App\Infra\Adapters\Mappers;
use Doctrine\ORM\Mapping as ORM;
use \App\Domain\Entity\Report as ReportDomain;

#[ORM\Entity]
#[ORM\Table(name: 'report')]
class Report
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private string $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    private User $user;

    #[ORM\Column(name: 'month', type: 'integer', length: 2, nullable: false)]
    private int $month;

    #[ORM\Column(name: 'year', type: 'integer', length: 4, nullable: false)]
    private int $year;

    #[ORM\Column(name: 'requested_at', type: 'date', nullable: false)]
    private \DateTime $requestedAt;

    #[ORM\Column(name: 'completed_at', type: 'date', nullable: true)]
    private ?\DateTime $completedAt;

    #[ORM\ManyToOne(targetEntity: ReportStatus::class)]
    #[ORM\JoinColumn(name: 'status', referencedColumnName: 'id', nullable: false)]
    private ReportStatus $status;

    public function __construct()
    {
        $this->setRequestedAt(new \DateTime());
        $this->setCompletedAt(null);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): Report
    {
        $this->id = $id;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): Report
    {
        $this->user = $user;
        return $this;
    }

    public function getMonth(): int
    {
        return $this->month;
    }

    public function setMonth(int $month): Report
    {
        $this->month = $month;
        return $this;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year): Report
    {
        $this->year = $year;
        return $this;
    }

    public function getRequestedAt(): \DateTime
    {
        return $this->requestedAt;
    }

    public function setRequestedAt(\DateTime $requestedAt): Report
    {
        $this->requestedAt = $requestedAt;
        return $this;
    }

    public function getCompletedAt(): ?\DateTime
    {
        return $this->completedAt;
    }

    public function setCompletedAt(?\DateTime $completedAt): Report
    {
        $this->completedAt = $completedAt;
        return $this;
    }

    public function getStatus(): ReportStatus
    {
        return $this->status;
    }

    public function setStatus(ReportStatus $status): Report
    {
        $this->status = $status;
        return $this;
    }

    public function toDomain(): ReportDomain
    {
        return new ReportDomain(
            $this->getUser()->getId(),
            $this->getMonth(),
            $this->getYear(),
            $this->getStatus()->getId(),
            $this->getId(),
            $this->getRequestedAt(),
        );
    }
}