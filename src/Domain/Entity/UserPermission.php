<?php

namespace App\Domain\Entity;

use App\Domain\Types\PermissionType;

readonly class UserPermission
{
    public function __construct(
        private int $id,
        private string $userId,
        private PermissionType $permissionType
    )
    {
    }

    public static function fromPrimitives(int $id, string $userId, PermissionType $permissionType): UserPermission
    {
        return new UserPermission($id, $userId, $permissionType);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getPermissionType(): PermissionType
    {
        return $this->permissionType;
    }
}
