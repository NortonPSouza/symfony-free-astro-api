<?php

namespace App\Infra\Adapters\Repository;

use App\App\Contracts\Repository\HoroscopeRepositoryInterface;
use App\Domain\Entity\Horoscope;
use App\Domain\Entity\Zodiac;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\Exceptions\RepositoryException;
use App\Infra\Adapters\Database\ConnectionDoctrine;
use App\Infra\Mappers\Zodiac as ZodiacMapper;
use App\Infra\Mappers\Horoscope as HoroscopeMapper;

readonly class HoroscopeRepository implements HoroscopeRepositoryInterface
{

    public function __construct(
        private ConnectionDoctrine $connection,
    )
    {
    }

    public function create(Horoscope $horoscope): void
    {
        try {
            $entityManager = $this->connection->getEntityManager();
            $zodiacMapper = $entityManager->getReference(ZodiacMapper::class, $horoscope->getZodiac()->getId());
            $horoscopeMapper = new HoroscopeMapper();
            $horoscopeMapper
               ->setMessage($horoscope->getMessage())
                ->setStartDate($horoscope->getStartDate())
                ->setEndDate($horoscope->getEndDate())
                ->setLuckNumber($horoscope->getLuckNumber())
                ->setZodiac($zodiacMapper);
            $entityManager->persist($horoscopeMapper);
        } catch (\Exception $exception) {
            throw new RepositoryException($exception->getMessage());
        }
    }

    public function find(Horoscope $horoscope): Horoscope
    {
        try {
            $entityManager = $this->connection->getEntityManager();
            $zodiacReference = $entityManager->getReference(ZodiacMapper::class, $horoscope->getZodiac()->getId());
            $horoscopeMapper = $entityManager
                ->getRepository(HoroscopeMapper::class)
                ->findOneBy(
                    ['zodiac' => $zodiacReference],
                    ['startDate' => 'DESC']
                );
        } catch (\Exception $exception) {
            throw new RepositoryException($exception->getMessage());
        }
        if (!$horoscopeMapper) {
            throw new NotFoundException('Horoscope not found');
        }
        return Horoscope::fromPrimitives(
            $horoscopeMapper->getId(),
            $horoscopeMapper->getStartDate(),
            $horoscopeMapper->getEndDate(),
            $horoscopeMapper->getMessage(),
            $horoscopeMapper->getLuckNumber(),
            Zodiac::fromPrimitives(
                $horoscopeMapper->getZodiac()->getId(),
                $horoscopeMapper->getZodiac()->getSign()
            )
        );
    }
}