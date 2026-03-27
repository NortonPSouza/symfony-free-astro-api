<?php

namespace App\Infra\Adapters\Repository;

use App\App\Contracts\Database\ConnectionInterface;
use App\App\Contracts\Repository\LoginRepositoryInterface;
use App\Domain\Entity\Login;
use App\Domain\Exceptions\NotFoundException;
use App\Infra\Adapters\Mappers\Login as LoginMapper;
use App\Domain\Exceptions\RepositoryException;

readonly class LoginRepository implements LoginRepositoryInterface
{

    public function __construct(
        private ConnectionInterface $connection
    )
    {
    }

    public function findByEmail(string $email): Login
    {
        try {
            $entityManager = $this->connection->getEntityManager();
            $loginMapper = $entityManager->getRepository(LoginMapper::class)->findOneBy(['email' => $email]);
            if (!$loginMapper) {
                throw new NotFoundException("login not found");
            }
            return $loginMapper->toDomain();
        } catch (\Exception $exception){
            throw new RepositoryException($exception->getMessage());
        }
    }

    public function updateToken(Login $login): void
    {
        try {
            $entityManager = $this->connection->getEntityManager();
            $loginMapper = $entityManager->getRepository(LoginMapper::class)->find($login->getId());
            if (!$loginMapper) {
                throw new NotFoundException("login not found");
            }
            $loginMapper
                ->setRefreshToken($login->getRefreshToken())
                ->setRefreshTokenExpiresAt($login->getExpiresIn());
            $entityManager->persist($loginMapper);
            $entityManager->flush();
        } catch (\Exception $exception){
            throw new RepositoryException($exception->getMessage());
        }
    }
}