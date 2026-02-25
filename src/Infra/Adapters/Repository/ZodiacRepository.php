<?php

namespace App\Infra\Adapters\Repository;

use App\App\Contracts\ZodiacRepositoryInterface;
use App\Domain\Exceptions\RepositoryException;
use App\Infra\Adapters\Database\ConnectionDoctrine;
use App\Infra\Adapters\Mappers\Zodiac;
use Doctrine\DBAL\Exception;

readonly class ZodiacRepository implements ZodiacRepositoryInterface
{

    public function __construct(
        private ConnectionDoctrine $connection,
    )
    {
    }

    /**
     * @throws RepositoryException
     */
    public function getSignByBirth(\DateTime $birth): Zodiac
    {
        try {
            $entityManager = $this->connection->getEntityManager();
            $monthDay = $birth->format('m-d');
            $queryBuilder = $entityManager->createQueryBuilder();
            return $queryBuilder->select('z')
                ->from(Zodiac::class, 'z')
                ->where(
                    $queryBuilder->expr()->andX(
                        $queryBuilder->expr()->lte('z.startDate', ':monthDay'),
                        $queryBuilder->expr()->gte('z.endDate', ':monthDay')
                    )
                )
                ->setParameter('monthDay', $monthDay)
                ->getQuery()
                ->getSingleResult();
        } catch (\Exception $exception) {
            throw new RepositoryException($exception->getMessage());
        }
    }
}