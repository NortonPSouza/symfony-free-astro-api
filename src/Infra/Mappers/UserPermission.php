<?php

namespace App\Infra\Mappers;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

#[ORM\Entity]
#[ORM\Table(name: 'user_permission')]
#[UniqueConstraint(name: 'uk_user_permission', columns: ['user_id', 'permission_type_id'])]
class UserPermission
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'integer', length: 8)]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    private User $user;

    #[ORM\ManyToOne(targetEntity: PermissionType::class, fetch: 'EAGER')]
    #[ORM\JoinColumn(name: 'permission_type_id', referencedColumnName: 'id', nullable: false)]
    private PermissionType $permissionType;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): UserPermission
    {
        $this->id = $id;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): UserPermission
    {
        $this->user = $user;
        return $this;
    }

    public function getPermissionType(): PermissionType
    {
        return $this->permissionType;
    }

    public function setPermissionType(PermissionType $permissionType): UserPermission
    {
        $this->permissionType = $permissionType;
        return $this;
    }
}
