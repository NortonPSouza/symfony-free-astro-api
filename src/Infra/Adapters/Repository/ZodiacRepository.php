<?php

namespace App\Infra\Adapters\Repository;

use App\App\Contracts\Repository\ZodiacRepositoryInterface;
use App\Domain\Entity\Zodiac as ZodiacDomain;
use App\Domain\Exceptions\RepositoryException;
use App\Infra\Adapters\Database\ConnectionDoctrine;
use App\Infra\Mappers\Zodiac;

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
    public function getSignByBirth(\DateTime $birth): ZodiacDomain
    {
        try {
            $entityManager = $this->connection->getEntityManager();
            $monthDay = '2000-' . $birth->format('m-d');
            $queryBuilder = $entityManager->createQueryBuilder();
            $zodiac = $queryBuilder->select('z')
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
            return ZodiacDomain::create($zodiac->getId(), $zodiac->getSign());
        } catch (\Exception $exception) {
            throw new RepositoryException($exception->getMessage());
        }
    }
}