<?php

namespace App\Infra\Adapters\Repository;

use App\App\Contracts\Repository\UserRepositoryInterface;
use App\Domain\Entity\User;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\Exceptions\RepositoryException;
use App\Infra\Adapters\Database\ConnectionDoctrine;
use App\Infra\Adapters\Mappers\Login;
use App\Infra\Adapters\Mappers\LoginUser;
use App\Infra\Adapters\Mappers\User as UserMapper;
use App\Infra\Adapters\Mappers\Zodiac;
use Doctrine\ORM\Exception\ORMException;

readonly class UserRepository implements UserRepositoryInterface
{

    public function __construct(
        private ConnectionDoctrine $connection
    )
    {
    }

    public function create(User $user): array
    {
        try {
            $entityManager = $this->connection->getEntityManager();
            $zodiacMapper = $entityManager->getReference(Zodiac::class, $user->getZodiac()->getId());
            $userMapper = new UserMapper();
            $userMapper
                ->setName($user->getName())
                ->setFamilyName($user->getFamilyName())
                ->setEmail($user->getEmail())
                ->setBirthDate($user->getBirthDate())
                ->setBirthTime($user->getBirthTime())
                ->setZodiac($zodiacMapper);
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

    public function find(string $id): ?User
    {
        try {
            $entityManager = $this->connection->getEntityManager();
            $userMapper = $entityManager->getRepository(UserMapper::class)->find($id);
            if (!$userMapper) {
                throw new NotFoundException("User not Found");
            }
            return $userMapper->toDomain();
        } catch (\Exception $exception) {
            throw new RepositoryException($exception->getMessage());
        }
    }

    public function findByEmail(string $email): User
    {
        try {
            $entityManager = $this->connection->getEntityManager();
            $userMapper = $entityManager->getRepository(UserMapper::class)
                ->findOneBy(['email' => $email]);
            if (!$userMapper) {
                throw new NotFoundException("User not Found");
            }
            return $userMapper->toDomain();
        } catch (\Exception $exception) {
            throw new RepositoryException($exception->getMessage());
        }
    }

    public function delete(User $user): array
    {
        try {
            $entityManager = $this->connection->getEntityManager();
            $userMapper = $entityManager->getRepository(UserMapper::class)->find($user->getId());
            if (!$userMapper) {
                throw new NotFoundException("User not Found");
            }
            $userId = ['id' => $userMapper->getId()];
            $entityManager->remove($userMapper);
            $entityManager->flush();
            return $userId;
        } catch (\Exception $exception) {
            throw new RepositoryException($exception->getMessage());
        }
    }
}