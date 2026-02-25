<?php

namespace App\Infra\Adapters\Repository;

use App\App\Contracts\UserRepositoryInterface;
use App\Domain\Exceptions\RepositoryException;
use App\Infra\Adapters\Database\ConnectionDoctrine;
use App\Domain\Entity\User;
use App\Infra\Adapters\Mappers\User as UserMapper;
use Doctrine\DBAL\Exception;

readonly class UserRepository implements UserRepositoryInterface
{

    public function __construct(
        private ConnectionDoctrine $connection,
    )
    {
    }

    /**
     * @throws Exception
     * @throws RepositoryException
     */
    public function create(User $user): ?array
    {
       $this->connection->begin();
        try {
            $entityManager = $this->connection->getEntityManager();
            $userMapper = new UserMapper();
            $userMapper
                ->setName($user->name)
                ->setFamilyName($user->familyName)
                ->setBirthDate($user->birthDate)
                ->setBirthTime($user->birthTime);
            $entityManager->persist($userMapper);
            return [
                'id' => $userMapper->getId()
            ];
        } catch (\Exception $exception) {
            $this->connection->rollback();
            throw new RepositoryException($exception->getMessage());
        }
    }

    public function list(): ?array
    {
        return [];
    }

    public function find(int $id): ?UserMapper
    {
       return null;
    }

    public function update(int $id): ?array
    {
        return [];
    }

    public function delete(int $id): ?array
    {
        return [];
    }
}