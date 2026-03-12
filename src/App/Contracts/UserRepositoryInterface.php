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

    public function find(int $id): ?UserMapper;

    public function delete(int $id): ?array;

}