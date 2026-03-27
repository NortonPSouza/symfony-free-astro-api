<?php

namespace App\App\Contracts\Repository;

use App\Domain\Entity\User;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\Exceptions\RepositoryException;
use Doctrine\ORM\Exception\ORMException;

interface UserRepositoryInterface
{

    /**
     * @param User $user
     * @return array|null
     * @throws ORMException
     * @throws RepositoryException
     */
    public function create(User $user): ?array;

    /**
     * @param string $id
     * @return User|null
     * @throws RepositoryException
     * @throws NotFoundException
     */
    public function find(string $id): ?User;

    /**
     * @param string $email
     * @return User
     * @throws RepositoryException
     * @throws NotFoundException
     */
    public function findByEmail(string $email): User;

    /**
     * @param User $user
     * @return array
     * @throws RepositoryException
     */
    public function delete(User $user): array;

}