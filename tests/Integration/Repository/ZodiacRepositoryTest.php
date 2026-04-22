<?php

namespace App\Tests\Integration\Repository;

use App\Domain\Exceptions\RepositoryException;
use App\Infra\Adapters\Database\ConnectionDoctrine;
use App\Infra\Adapters\Repository\ZodiacRepository;
use App\Tests\Integration\IntegrationTestCase;

class ZodiacRepositoryTest extends IntegrationTestCase
{
    private ZodiacRepository $zodiacRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $connection = new ConnectionDoctrine($this->entityManager);
        $this->zodiacRepository = new ZodiacRepository($connection);
    }

    public function testGetSignByBirthTaurus(): void
    {
        $zodiac = $this->zodiacRepository->getSignByBirth(new \DateTime('1990-05-15'));
        $this->assertEquals('Taurus', $zodiac->getSign());
    }

    public function testGetSignByBirthAries(): void
    {
        $zodiac = $this->zodiacRepository->getSignByBirth(new \DateTime('2000-04-10'));
        $this->assertEquals('Aries', $zodiac->getSign());
    }

    public function testGetSignByBirthCapricorn(): void
    {
        $zodiac = $this->zodiacRepository->getSignByBirth(new \DateTime('2000-01-05'));
        $this->assertEquals('Capricorn', $zodiac->getSign());
    }

    public function testGetSignByBirthPisces(): void
    {
        $zodiac = $this->zodiacRepository->getSignByBirth(new \DateTime('2000-03-10'));
        $this->assertEquals('Pisces', $zodiac->getSign());
    }
}
