<?php

namespace App\App\Contracts;

use App\Infra\Adapters\Mappers\User;

interface UserRepositoryInterface
{
    public function create(array $user): ?array;

    public function list(): ?array;

    public function find(int $id): ?User;

    public function update(int $id): ?array;

    public function delete(int $id): ?array;

}