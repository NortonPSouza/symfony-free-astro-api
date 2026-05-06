<?php

namespace App\Tests\Integration\Repository;

use App\Domain\Builder\HoroscopeBuilder;
use App\Domain\Entity\Horoscope;
use App\Domain\Entity\Zodiac;
use App\Domain\Exceptions\NotFoundException;
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

    public function testFindByZodiacId(): void
    {
        $zodiac = $this->getZodiac();
        $horoscope = (new HoroscopeBuilder())
            ->withStartDate(new \DateTime('2025-01-06'))
            ->withEndDate(new \DateTime('2025-01-12'))
            ->withMessage('Found by zodiac id')
            ->withLuckNumber(42)
            ->withZodiac($zodiac)
            ->build();

        $this->horoscopeRepository->create($horoscope);
        $this->connection->flush();
        $this->entityManager->clear();

        $search = (new HoroscopeBuilder())
            ->withZodiac(Zodiac::fromPrimitives($zodiac->getId(), ''))
            ->build();

        $found = $this->horoscopeRepository->find($search);

        $this->assertEquals('Found by zodiac id', $found->getMessage());
        $this->assertEquals(42, $found->getLuckNumber());
        $this->assertEquals($zodiac->getId(), $found->getZodiac()->getId());
    }

    public function testFindReturnsLatestHoroscope(): void
    {
        $zodiac = $this->getZodiac();

        $older = (new HoroscopeBuilder())
            ->withStartDate(new \DateTime('2025-01-01'))
            ->withEndDate(new \DateTime('2025-01-05'))
            ->withMessage('Older horoscope')
            ->withLuckNumber(1)
            ->withZodiac($zodiac)
            ->build();

        $newer = (new HoroscopeBuilder())
            ->withStartDate(new \DateTime('2025-01-06'))
            ->withEndDate(new \DateTime('2025-01-12'))
            ->withMessage('Newer horoscope')
            ->withLuckNumber(2)
            ->withZodiac($zodiac)
            ->build();

        $this->horoscopeRepository->create($older);
        $this->horoscopeRepository->create($newer);
        $this->connection->flush();
        $this->entityManager->clear();

        $search = (new HoroscopeBuilder())
            ->withZodiac(Zodiac::fromPrimitives($zodiac->getId(), ''))
            ->build();

        $found = $this->horoscopeRepository->find($search);

        $this->assertEquals('Newer horoscope', $found->getMessage());
    }

    public function testFindNotFound(): void
    {
        $this->expectException(NotFoundException::class);

        $search = (new HoroscopeBuilder())
            ->withZodiac(Zodiac::fromPrimitives('00000000-0000-0000-0000-000000000000', ''))
            ->build();

        $this->horoscopeRepository->find($search);
    }
}
