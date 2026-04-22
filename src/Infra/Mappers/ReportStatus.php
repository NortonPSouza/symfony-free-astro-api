<?php

namespace App\Infra\Mappers;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'report_status')]
class ReportStatus
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'integer', length: 8)]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    private int $id;

    #[ORM\Column(name: 'description', type: 'string', length: 16, nullable: false)]
    private string $description;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): ReportStatus
    {
        $this->id = $id;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): ReportStatus
    {
        $this->description = $description;
        return $this;
    }

}