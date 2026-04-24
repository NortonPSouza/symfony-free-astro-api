<?php

namespace App\Tests\Unit\UseCase\User;

use App\App\Contracts\Repository\UserRepositoryInterface;
use App\App\Contracts\Repository\ZodiacRepositoryInterface;
use App\App\Contracts\Validation\PasswordEncoderInterface;
use App\App\UseCase\User\Create\CreateUserUseCase;
use App\App\UseCase\User\Create\Input\CreateUserInput;
use App\Domain\Entity\User;
use App\Domain\Entity\Zodiac;
use App\Domain\Exceptions\InvalidParamsException;
use App\Domain\Exceptions\RepositoryException;
use PHPUnit\Framework\TestCase;

class CreateUserUseCaseTest extends TestCase
{
    private UserRepositoryInterface $userRepository;
    private ZodiacRepositoryInterface $zodiacRepository;
    private PasswordEncoderInterface $passwordEncoder;
    private CreateUserUseCase $useCase;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->zodiacRepository = $this->createMock(ZodiacRepositoryInterface::class);
        $this->passwordEncoder = $this->createMock(PasswordEncoderInterface::class);

        $this->useCase = new CreateUserUseCase(
            userRepository: $this->userRepository,
            zodiacRepository: $this->zodiacRepository,
            passwordEncoder: $this->passwordEncoder
        );
    }

    public function testCreateUserSuccess(): void
    {
        $input = CreateUserInput::fromArray([
            'name' => 'John',
            'family_name' => 'Doe',
            'password' => 'secret123',
            'email' => 'john@example.com',
            'birth_date' => '1990-05-15',
        ]);

        $zodiac = Zodiac::create('uuid-zodiac', 'Taurus');

        $this->zodiacRepository
            ->expects($this->once())
            ->method('getSignByBirth')
            ->willReturn($zodiac);

        $this->passwordEncoder
            ->expects($this->once())
            ->method('encode')
            ->willReturn('$2y$10$hashedpassword');

        $this->userRepository
            ->expects($this->once())
            ->method('create')
            ->willReturn(User::fromPrimitives(
                'uuid-user-123',
                'John',
                'Doe',
                'john@example.com',
                new \DateTime('1990-05-15')
            ));

        $output = $this->useCase->execute($input);

        $this->assertEquals(201, $output->getCode());
        $this->assertEquals(['id' => 'uuid-user-123'], $output->getData());
    }

    public function testCreateUserRepositoryFailure(): void
    {
        $input = CreateUserInput::fromArray([
            'name' => 'John',
            'family_name' => 'Doe',
            'password' => 'secret123',
            'email' => 'john@example.com',
            'birth_date' => '1990-05-15',
        ]);

        $zodiac = Zodiac::create('uuid-zodiac', 'Taurus');

        $this->zodiacRepository
            ->expects($this->once())
            ->method('getSignByBirth')
            ->willReturn($zodiac);

        $this->passwordEncoder
            ->method('encode')
            ->willReturn('$2y$10$hashedpassword');

        $this->userRepository
            ->expects($this->once())
            ->method('create')
            ->willThrowException(new RepositoryException('Duplicate entry'));

        $output = $this->useCase->execute($input);

        $this->assertEquals(500, $output->getCode());
    }

    public function testCreateUserInvalidInput(): void
    {
        $this->expectException(InvalidParamsException::class);

        CreateUserInput::fromArray([
            'name' => '',
            'family_name' => '',
            'password' => '',
            'email' => 'invalid-email',
            'birth_date' => '1990-05-15',
        ]);
    }
}
