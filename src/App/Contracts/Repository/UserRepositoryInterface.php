<?php

namespace App\App\Contracts\Repository;

use App\Domain\Entity\User;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\Exceptions\RepositoryException;

interface UserRepositoryInterface
{

    /**
     * @throws RepositoryException
     *
     * @param User $user
     * @return array|null
     */
    public function create(User $user): ?array;

    /**
     * @throws RepositoryException
     * @throws NotFoundException
     *
     * @param int $id
     * @return User|null
     */
    public function find(string $id): ?User;

    /**
     * @param User $user
     * @return array
     * @throws RepositoryException
     */
    public function delete(User $user): array;

}