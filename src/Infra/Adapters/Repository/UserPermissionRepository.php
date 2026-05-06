<?php

namespace App\Infra\Adapters\Repository;

use App\App\Contracts\Repository\UserPermissionRepositoryInterface;
use App\Domain\Entity\User;
use App\Domain\Entity\UserPermission as UserPermissionDomain;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\Exceptions\RepositoryException;
use App\Domain\Types\PermissionType;
use App\Infra\Adapters\Database\ConnectionDoctrine;
use App\Infra\Mappers\UserPermission;

readonly class UserPermissionRepository implements UserPermissionRepositoryInterface
{
    public function __construct(
        private ConnectionDoctrine $connection,
    )
    {
    }

    public function find(User $user): UserPermissionDomain
    {
        try {
            $userPermissionMapper = $this->connection->getEntityManager()
                ->getRepository(UserPermission::class)
                ->findOneBy(['user' => $user->getId()]);
        } catch (\Exception $exception) {
            throw new RepositoryException($exception->getMessage());
        }
        if (!$userPermissionMapper) {
            throw new NotFoundException('User permission not found');
        }
        return UserPermissionDomain::fromPrimitives(
            $userPermissionMapper->getId(),
            $userPermissionMapper->getUser()->getId(),
            PermissionType::from($userPermissionMapper->getPermissionType()->getId())
        );
    }
}
