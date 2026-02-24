<?php

namespace App\Infra\Adapters\Database;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;

readonly class ConnectionDoctrine implements ConnectionInterface
{

    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    /**
     * @throws Exception
     */
    public function begin(): void
    {
        $this->entityManager->getConnection()->beginTransaction();
    }

    /**
     * @throws Exception
     */
    public function commit(): void
    {
        $this->entityManager->flush();
        $this->entityManager->getConnection()->commit();
    }

    /**
     * @throws Exception
     */
    public function rollback(): void
    {
        $this->entityManager->getConnection()->rollBack();
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }
}