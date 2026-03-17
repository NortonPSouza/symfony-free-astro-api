<?php

namespace App\Infra\Adapters\Mappers;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'report')]
class Report
{

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private string $processId;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    private User $user;

    #[ORM\Column(name: 'month', type: 'integer', length: 2, nullable: false)]
    private int $month;

    #[ORM\Column(name: 'year', type: 'integer', length: 4, nullable: false)]
    private int $year;

    #[ORM\Column(name: 'status', type: 'string', length: 16, nullable: false)]
    private int $status;

    #[ORM\Column(name: 'requested_at', type: 'date', nullable: false)]
    private \DateTime $requestedAt;

    #[ORM\Column(name: 'completed_at', type: 'date', nullable: false)]
    private \DateTime $completedAt;

    public function __construct()
    {
        $this->setRequestedAt(new \DateTime());
    }

    public function getProcessId(): string
    {
        return $this->processId;
    }

    public function setProcessId(string $processId): Report
    {
        $this->processId = $processId;
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

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): Report
    {
        $this->status = $status;
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

    public function getCompletedAt(): \DateTime
    {
        return $this->completedAt;
    }

    public function setCompletedAt(\DateTime $completedAt): Report
    {
        $this->completedAt = $completedAt;
        return $this;
    }

}