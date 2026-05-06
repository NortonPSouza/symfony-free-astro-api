<?php

namespace App\App\Contracts\Repository;

use App\Domain\Entity\User;
use App\Domain\Entity\UserPermission;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\Exceptions\RepositoryException;

interface UserPermissionRepositoryInterface
{
    /**
     * @param User $user
     * @return UserPermission
     * @throws RepositoryException
     * @throws NotFoundException
     */
    public function find(User $user): UserPermission;
}
