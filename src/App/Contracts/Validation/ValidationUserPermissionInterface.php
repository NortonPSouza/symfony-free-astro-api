<?php

namespace App\App\Contracts\Validation;

use App\Domain\Exceptions\ForbiddenException;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\Exceptions\RepositoryException;
use App\Domain\Types\PermissionType;

interface ValidationUserPermissionInterface
{

    /**
     * @param string $userId
     * @param PermissionType $permissionType
     * @return void
     * @throws RepositoryException
     * @throws NotFoundException
     * @throws ForbiddenException
 */
    public function validate(string $userId, PermissionType $permissionType): void;
}