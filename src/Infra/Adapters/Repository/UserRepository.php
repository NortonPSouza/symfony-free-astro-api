<?php

namespace App\Infra\Adapters\Repository;

use App\App\Contracts\Repository\UserRepositoryInterface;
use App\Domain\Entity\User;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\Exceptions\RepositoryException;
use App\Domain\ValueObjects\Password;
use App\Infra\Adapters\Database\ConnectionDoctrine;
use App\Infra\Mappers\Login;
use App\Infra\Mappers\LoginUser;
use App\Infra\Mappers\User as UserMapper;
use App\Infra\Mappers\Zodiac;

readonly class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private ConnectionDoctrine $connection
    ) {
    }

    public function create(User $user): User
    {
        try {
            $entityManager = $this->connection->getEntityManager();
            $zodiacMapper = $entityManager->getReference(Zodiac::class, $user->getZodiac()->getId());
            $userMapper = new UserMapper();
            $userMapper
                ->setName($user->getName())
                ->setFamilyName($user->getFamilyName())
                ->setEmail($user->getEmail()->getValue())
                ->setBirthDate($user->getBirthDate())
                ->setBirthTime($user->getBirthTime())
                ->setZodiac($zodiacMapper);
            $entityManager->persist($userMapper);
            $loginMapper = new Login();
            $loginMapper
                ->setEmail($user->getEmail()->getValue())
                ->setPassword($user->getPassword()->getValue());
            $entityManager->persist($loginMapper);
            $loginUserMapper = new LoginUser();
            $loginUserMapper
                ->setLogin($loginMapper)
                ->setUser($userMapper);
            $entityManager->persist($loginUserMapper);
            $entityManager->flush();
            return User::fromPrimitives(
                $userMapper->getId(),
                $userMapper->getName(),
                $userMapper->getFamilyName(),
                $userMapper->getEmail(),
                $userMapper->getBirthDate(),
                $userMapper->getBirthTime(),
                $userMapper->getZodiac()?->getId(),
                $userMapper->getZodiac()?->getSign()
            );
        } catch (\Exception $exception) {
            throw new RepositoryException($exception->getMessage());
        }
    }

    public function find(string $id): ?User
    {
        try {
            $entityManager = $this->connection->getEntityManager();
            $userMapper = $entityManager->getRepository(UserMapper::class)->find($id);
        } catch (\Exception $exception) {
            throw new RepositoryException($exception->getMessage());
        }
        if (!$userMapper) {
            throw new NotFoundException("User not Found");
        }
        return User::fromPrimitives(
            $userMapper->getId(),
            $userMapper->getName(),
            $userMapper->getFamilyName(),
            $userMapper->getEmail(),
            $userMapper->getBirthDate(),
            $userMapper->getBirthTime(),
            $userMapper->getZodiac()?->getId(),
            $userMapper->getZodiac()?->getSign()
        );
    }

    public function findByEmail(string $email): User
    {
        try {
            $entityManager = $this->connection->getEntityManager();
            $userMapper = $entityManager->getRepository(UserMapper::class)
                ->findOneBy(['email' => $email]);
            $loginMapper = $entityManager->getRepository(Login::class)
                ->findOneBy(['email' => $email]);
        } catch (\Exception $exception) {
            throw new RepositoryException($exception->getMessage());
        }
        if (!$userMapper) {
            throw new NotFoundException("User not Found");
        }
        if (!$loginMapper) {
            throw new NotFoundException("Login not Found");
        }
        $userDomain = User::fromPrimitives(
            $userMapper->getId(),
            $userMapper->getName(),
            $userMapper->getFamilyName(),
            $userMapper->getEmail(),
            $userMapper->getBirthDate(),
            $userMapper->getBirthTime(),
            $userMapper->getZodiac()?->getId(),
            $userMapper->getZodiac()?->getSign()
        );
        $userDomain->setPassword(Password::fromHash($loginMapper->getPassword()));
        return $userDomain;
    }

    public function delete(User $user): array
    {
        try {
            $entityManager = $this->connection->getEntityManager();
            $userMapper = $entityManager->getRepository(UserMapper::class)->find($user->getId());
        } catch (\Exception $exception) {
            throw new RepositoryException($exception->getMessage());
        }
        if (!$userMapper) {
            throw new NotFoundException("User not Found");
        }
        try {
            $userId = ['id' => $userMapper->getId()];
            $entityManager->remove($userMapper);
            $entityManager->flush();
            return $userId;
        } catch (\Exception $exception) {
            throw new RepositoryException($exception->getMessage());
        }
    }
}
