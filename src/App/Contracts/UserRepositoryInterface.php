<?php

namespace App\App\Contracts;

use App\Domain\Entity\User;
use App\Domain\Exceptions\RepositoryException;
use App\Infra\Adapters\Mappers\User as UserMapper;

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
     *
     * @param int $id
     * @return UserMapper|null
     */
    public function find(int $id): ?UserMapper;

    /**
     * @param UserMapper $user
     * @return array
     * @throws RepositoryException
     */
    public function delete(UserMapper $user): array;

}