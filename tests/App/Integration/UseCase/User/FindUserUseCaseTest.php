<?php

namespace App\Tests\App\Integration\UseCase\User;

use App\App\Contracts\Repository\UserRepositoryInterface;
use App\App\UseCase\User\Find\FindUserUseCase;
use App\App\UseCase\User\Find\Input\FindUserInput;
use App\Domain\Entity\User;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\ValueObjects\Email;
use PHPUnit\Framework\TestCase;

class FindUserUseCaseTest extends TestCase
{
    private UserRepositoryInterface $userRepository;
    private FindUserUseCase $useCase;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->useCase = new FindUserUseCase(userRepository: $this->userRepository);
    }

    public function testFindUserSuccess(): void
    {
        $user = new User(
            'uuid-123',
            'John',
            'Doe',
            Email::create('john@example.com'),
            null,
            new \DateTime('1990-05-15'),
            null,
            null
        );

        $this->userRepository
            ->expects($this->once())
            ->method('find')
            ->with('uuid-123')
            ->willReturn($user);

        $input = FindUserInput::fromArray('uuid-123');
        $output = $this->useCase->execute($input);

        $this->assertEquals(200, $output->getCode());
        $this->assertEquals('uuid-123', $output->getData()['id']);
        $this->assertEquals('John', $output->getData()['name']);
    }

    public function testFindUserNotFound(): void
    {
        $this->userRepository
            ->expects($this->once())
            ->method('find')
            ->with('uuid-not-exists')
            ->willThrowException(new NotFoundException('User not Found'));

        $input = FindUserInput::fromArray('uuid-not-exists');
        $output = $this->useCase->execute($input);

        $this->assertEquals(404, $output->getCode());
    }
}
