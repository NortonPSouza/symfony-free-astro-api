<?php

namespace App\Infra\Adapters\Repository;

use App\App\Contracts\UserRepositoryInterface;
use App\Domain\Exceptions\RepositoryException;
use App\Infra\Adapters\Database\ConnectionDoctrine;
use App\Domain\Entity\User;
use App\Infra\Adapters\Mappers\Login;
use App\Infra\Adapters\Mappers\LoginUser;
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
     * @throws RepositoryException
     */
    public function create(User $user): array
    {
        try {
            $entityManager = $this->connection->getEntityManager();
            $userMapper = new UserMapper();
            $userMapper
                ->setName($user->getName())
                ->setFamilyName($user->getFamilyName())
                ->setBirthDate($user->getBirthDate())
                ->setBirthTime($user->getBirthTime())
                ->setZodiac($user->getZodiac());
            $entityManager->persist($userMapper);
            $loginMapper = new Login();
            $loginMapper
                ->setEmail($user->getEmail())
                ->setPassword($user->getPassword());
            $entityManager->persist($loginMapper);
            $loginUserMapper = new LoginUser();
            $loginUserMapper
                ->setLogin($loginMapper)
                ->setUser($userMapper);
            $entityManager->persist($loginUserMapper);
            $entityManager->flush();
            return [
                'id' => $userMapper->getId()
            ];
        } catch (\Exception $exception) {
            throw new RepositoryException($exception->getMessage());
        }
    }

    public function find(int $id): ?UserMapper
    {
       return null;
    }

    public function delete(int $id): ?array
    {
        return [];
    }
}