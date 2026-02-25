<?php

namespace App\App\Contracts;

use App\Domain\Entity\User;
use App\Infra\Adapters\Mappers\User as UserMapper;

interface UserRepositoryInterface
{
    public function create(User $user): ?array;

    public function list(): ?array;

    public function find(int $id): ?UserMapper;

    public function update(int $id): ?array;

    public function delete(int $id): ?array;

}