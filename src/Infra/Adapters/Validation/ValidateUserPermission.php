<?php

namespace App\Infra\Adapters\Validation;

use App\App\Contracts\Repository\UserPermissionRepositoryInterface;
use App\App\Contracts\Repository\UserRepositoryInterface;
use App\App\Contracts\Validation\ValidationUserPermissionInterface;
use App\Domain\Exceptions\ForbiddenException;
use App\Domain\Types\PermissionType;

readonly class ValidateUserPermission implements ValidationUserPermissionInterface
{

    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserPermissionRepositoryInterface $userPermissionRepository
    )
    {
    }

    public function validate(string $userId, PermissionType $permissionType): void
    {
       $userDomain = $this->userRepository->find($userId);
       $userPermissionDomain = $this->userPermissionRepository->find($userDomain);
       if($userPermissionDomain->getPermissionType() !== $permissionType) {
           throw new ForbiddenException("User not have access this resource");
       }
    }
}