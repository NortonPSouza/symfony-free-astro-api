<?php

namespace App\Tests\Integration\Repository;

use App\Domain\Builder\HoroscopeBuilder;
use App\Domain\Entity\Zodiac;
use App\Infra\Adapters\Database\ConnectionDoctrine;
use App\Infra\Adapters\Repository\HoroscopeRepository;
use App\Infra\Adapters\Repository\ZodiacRepository;
use App\Infra\Mappers\Horoscope as HoroscopeMapper;
use App\Tests\Integration\IntegrationTestCase;

class HoroscopeRepositoryTest extends IntegrationTestCase
{
    private HoroscopeRepository $horoscopeRepository;
    private ZodiacRepository $zodiacRepository;
    private ConnectionDoctrine $connection;

    protected function setUp(): void
    {
        parent::setUp();
        $this->connection = new ConnectionDoctrine($this->entityManager);
        $this->horoscopeRepository = new HoroscopeRepository($this->connection);
        $this->zodiacRepository = new ZodiacRepository($this->connection);
    }

    private function getZodiac(): Zodiac
    {
        return $this->zodiacRepository->getSignByBirth(new \DateTime('1990-03-25'));
    }

    public function testCreateSingleHoroscope(): void
    {
        $zodiac = $this->getZodiac();
        $horoscope = (new HoroscopeBuilder())
            ->withStartDate(new \DateTime('2025-01-06'))
            ->withEndDate(new \DateTime('2025-01-12'))
            ->withMessage('Great week ahead')
            ->withLuckNumber(7)
            ->withZodiac($zodiac)
            ->build();

        $this->horoscopeRepository->create($horoscope);
        $this->connection->flush();

        $result = $this->entityManager
            ->getRepository(HoroscopeMapper::class)
            ->findOneBy(['message' => 'Great week ahead']);

        $this->assertNotNull($result);
        $this->assertEquals(7, $result->getLuckNumber());
        $this->assertEquals($zodiac->getId(), $result->getZodiac()->getId());
    }

    public function testCreateAllWithTransactionCommit(): void
    {
        $zodiac = $this->getZodiac();

        $this->connection->begin();

        for ($i = 0; $i < 3; $i++) {
            $horoscope = (new HoroscopeBuilder())
                ->withStartDate(new \DateTime('2025-01-06'))
                ->withEndDate(new \DateTime('2025-01-12'))
                ->withMessage("Message $i")
                ->withLuckNumber($i + 1)
                ->withZodiac($zodiac)
                ->build();
            $this->horoscopeRepository->create($horoscope);
        }

        $this->connection->commit();

        $results = $this->entityManager
            ->getRepository(HoroscopeMapper::class)
            ->findBy(['zodiac' => $zodiac->getId()]);

        $this->assertCount(3, $results);
    }

    public function testCreateAllWithTransactionRollback(): void
    {
        $zodiac = $this->getZodiac();

        $this->connection->begin();

        $horoscope = (new HoroscopeBuilder())
            ->withStartDate(new \DateTime('2025-01-06'))
            ->withEndDate(new \DateTime('2025-01-12'))
            ->withMessage('Should not persist')
            ->withLuckNumber(99)
            ->withZodiac($zodiac)
            ->build();

        $this->horoscopeRepository->create($horoscope);
        $this->connection->rollback();
        $this->entityManager->clear();

        $result = $this->entityManager
            ->getRepository(HoroscopeMapper::class)
            ->findOneBy(['message' => 'Should not persist']);

        $this->assertNull($result);
    }
}
