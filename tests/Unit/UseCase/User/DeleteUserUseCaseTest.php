<?php

namespace App\Tests\Unit\UseCase\User;

use App\App\Contracts\Repository\UserRepositoryInterface;
use App\App\UseCase\User\Delete\DeleteUserUseCase;
use App\App\UseCase\User\Delete\Input\DeleteUserInput;
use App\Domain\Entity\User;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\ValueObjects\Email;
use PHPUnit\Framework\TestCase;

class DeleteUserUseCaseTest extends TestCase
{
    private UserRepositoryInterface $userRepository;
    private DeleteUserUseCase $useCase;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->useCase = new DeleteUserUseCase(userRepository: $this->userRepository);
    }

    public function testDeleteUserSuccess(): void
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

        $this->userRepository
            ->expects($this->once())
            ->method('delete')
            ->with($user)
            ->willReturn($user);

        $input = DeleteUserInput::fromArray('uuid-123');
        $output = $this->useCase->execute($input);

        $this->assertEquals(200, $output->getCode());
        $this->assertEquals(['id' => 'uuid-123'], $output->getData());
    }

    public function testDeleteUserNotFound(): void
    {
        $this->userRepository
            ->expects($this->once())
            ->method('find')
            ->with('uuid-not-exists')
            ->willThrowException(new NotFoundException('User not Found'));

        $input = DeleteUserInput::fromArray('uuid-not-exists');
        $output = $this->useCase->execute($input);

        $this->assertEquals(404, $output->getCode());
    }
}
