<?php

namespace App\Infra\Mappers;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'permission_type')]
class PermissionType
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'integer', length: 8)]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    private int $id;

    #[ORM\Column(name: 'description', type: 'string', length: 60, nullable: false)]
    private string $description;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): PermissionType
    {
        $this->id = $id;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): PermissionType
    {
        $this->description = $description;
        return $this;
    }
}
