<?php

namespace App\Tests\Integration\Repository;

use App\Domain\Builder\UserBuilder;
use App\Domain\Entity\User;
use App\Domain\Entity\Zodiac;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\ValueObjects\Email;
use App\Infra\Adapters\Database\ConnectionDoctrine;
use App\Infra\Adapters\Repository\UserRepository;
use App\Infra\Adapters\Repository\ZodiacRepository;
use App\Tests\Integration\IntegrationTestCase;

class UserRepositoryTest extends IntegrationTestCase
{
    private UserRepository $userRepository;
    private ZodiacRepository $zodiacRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $connection = new ConnectionDoctrine($this->entityManager);
        $this->userRepository = new UserRepository($connection);
        $this->zodiacRepository = new ZodiacRepository($connection);
    }

    private function getZodiac(): Zodiac
    {
        return $this->zodiacRepository->getSignByBirth(new \DateTime('1990-05-15'));
    }

    private function createUser(): array
    {
        $zodiac = $this->getZodiac();
        $user = (new UserBuilder())
            ->withName('John')
            ->withFamilyName('Doe')
            ->withEmail('john_test@example.com')
            ->withPassword('secret123')
            ->withBirthDate(new \DateTime('1990-05-15'))
            ->withZodiac($zodiac)
            ->build();
        return $this->userRepository->create($user);
    }

    public function testCreateAndFindUser(): void
    {
        $created = $this->createUser();
        $this->assertArrayHasKey('id', $created);
        $user = $this->userRepository->find($created['id']);
        $this->assertEquals('John', $user->getName());
        $this->assertEquals('Doe', $user->getFamilyName());
        $this->assertEquals('john_test@example.com', $user->getEmail()->getValue());
    }

    public function testFindByEmail(): void
    {
        $this->createUser();
        $user = $this->userRepository->findByEmail('john_test@example.com');
        $this->assertEquals('John', $user->getName());
        $this->assertNotNull($user->getPassword());
    }

    public function testFindUserNotFound(): void
    {
        $this->expectException(NotFoundException::class);
        $this->userRepository->find('00000000-0000-0000-0000-000000000000');
    }

    public function testFindByEmailNotFound(): void
    {
        $this->expectException(NotFoundException::class);
        $this->userRepository->findByEmail('nonexistent@example.com');
    }

    public function testDeleteUserNotFound(): void
    {
        $user = new User(
            '00000000-0000-0000-0000-000000000000',
            'Ghost',
            'User',
            Email::create('ghost@example.com'),
            null,
            new \DateTime('1990-01-01'),
            null,
            null
        );
        $this->expectException(NotFoundException::class);
        $this->userRepository->delete($user);
    }
}
