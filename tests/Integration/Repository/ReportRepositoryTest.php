<?php

namespace App\Tests\Integration\Repository;

use App\Domain\Builder\ReportBuilder;
use App\Domain\Builder\UserBuilder;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\Types\ReportStatus;
use App\Infra\Adapters\Database\ConnectionDoctrine;
use App\Infra\Adapters\Repository\ReportRepository;
use App\Infra\Adapters\Repository\UserRepository;
use App\Infra\Adapters\Repository\ZodiacRepository;
use App\Tests\Integration\IntegrationTestCase;

class ReportRepositoryTest extends IntegrationTestCase
{
    private ReportRepository $reportRepository;
    private UserRepository $userRepository;
    private ZodiacRepository $zodiacRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $connection = new ConnectionDoctrine($this->entityManager);
        $this->reportRepository = new ReportRepository($connection);
        $this->userRepository = new UserRepository($connection);
        $this->zodiacRepository = new ZodiacRepository($connection);
    }

    private function createUser(): string
    {
        $zodiac = $this->zodiacRepository->getSignByBirth(new \DateTime('1990-05-15'));
        $user = (new UserBuilder())
            ->withName('Report')
            ->withFamilyName('Tester')
            ->withEmail('report_test@example.com')
            ->withPassword('secret123')
            ->withBirthDate(new \DateTime('1990-05-15'))
            ->withZodiac($zodiac)
            ->build();
        $created = $this->userRepository->create($user);
        return $created['id'];
    }

    public function testCreateReport(): void
    {
        $userId = $this->createUser();
        $report = (new ReportBuilder())
            ->withUserId($userId)
            ->withMonth(6)
            ->withYear(2025)
            ->build();
        $created = $this->reportRepository->create($report);
        $this->assertNotNull($created->getProcessId());
        $this->assertEquals($userId, $created->getUserId());
        $this->assertEquals(6, $created->getMonth());
        $this->assertEquals(2025, $created->getYear());
        $this->assertEquals(ReportStatus::PENDING->getStatus(), $created->getStatus());
    }

    public function testFindById(): void
    {
        $userId = $this->createUser();
        $report = (new ReportBuilder())
            ->withUserId($userId)
            ->withMonth(7)
            ->withYear(2025)
            ->build();
        $created = $this->reportRepository->create($report);
        $found = $this->reportRepository->findById($created->getProcessId());
        $this->assertEquals($created->getProcessId(), $found->getProcessId());
        $this->assertEquals(7, $found->getMonth());
    }

    public function testFindByIdNotFound(): void
    {
        $this->expectException(NotFoundException::class);
        $this->reportRepository->findById('00000000-0000-0000-0000-000000000000');
    }

    public function testUpdateStatus(): void
    {
        $userId = $this->createUser();
        $report = (new ReportBuilder())
            ->withUserId($userId)
            ->withMonth(8)
            ->withYear(2025)
            ->build();
        $created = $this->reportRepository->create($report);
        $updated = $this->reportRepository->updateStatus($created->getProcessId(), ReportStatus::COMPLETED);
        $this->assertEquals(ReportStatus::COMPLETED->getStatus(), $updated->getStatus());
    }
}
