<?php

namespace App\Tests\App\Integration\UseCase\Authenticate;

use App\App\Contracts\Gateway\TokenManagerInterface;
use App\App\Contracts\Memory\TokenMemoryInterface;
use App\App\Contracts\Repository\UserRepositoryInterface;
use App\App\Contracts\Validation\PasswordEncoderInterface;
use App\App\UseCase\Authenticate\AuthenticateUseCase;
use App\App\UseCase\Authenticate\Input\AuthenticateInput;
use App\Domain\Entity\Session;
use App\Domain\Entity\User;
use App\Domain\Exceptions\InvalidParamsException;
use App\Domain\ValueObjects\Token;
use PHPUnit\Framework\TestCase;

class AuthenticateUseCaseTest extends TestCase
{
    private UserRepositoryInterface $userRepository;
    private PasswordEncoderInterface $passwordEncoder;
    private TokenManagerInterface $tokenManager;
    private TokenMemoryInterface $tokenMemory;
    private AuthenticateUseCase $useCase;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->passwordEncoder = $this->createMock(PasswordEncoderInterface::class);
        $this->tokenManager = $this->createMock(TokenManagerInterface::class);
        $this->tokenMemory = $this->createMock(TokenMemoryInterface::class);

        $this->useCase = new AuthenticateUseCase(
            userRepository: $this->userRepository,
            passwordEncoder: $this->passwordEncoder,
            tokenManager: $this->tokenManager,
            tokenMemory: $this->tokenMemory
        );
    }

    private function buildUser(): User
    {
        return new User(
            'uuid-123',
            'John',
            'Doe',
            'john@example.com',
            '$2y$10$hashedpassword',
            new \DateTime('1990-05-15'),
            null,
            null
        );
    }

    public function testAuthenticateWithTokenSuccess(): void
    {
        $input = AuthenticateInput::fromArray([
            'email' => 'john@example.com',
            'password' => 'secret123',
            'grant_type' => 'token',
        ]);

        $user = $this->buildUser();

        $this->userRepository
            ->expects($this->once())
            ->method('findByEmail')
            ->with('john@example.com')
            ->willReturn($user);

        $this->passwordEncoder
            ->expects($this->once())
            ->method('verify')
            ->with('secret123', '$2y$10$hashedpassword');

        $this->tokenManager
            ->expects($this->exactly(2))
            ->method('generate')
            ->willReturnOnConsecutiveCalls(
                new Token('refresh-token-abc', 604800),
                new Token('access-token-xyz', 900),
            );

        $this->tokenMemory
            ->expects($this->once())
            ->method('setSessions');

        $output = $this->useCase->execute($input);

        $this->assertEquals(201, $output->getCode());
        $this->assertArrayHasKey('access_token', $output->getData());
        $this->assertArrayHasKey('refresh_token', $output->getData());
    }

    public function testAuthenticateWithRefreshTokenSuccess(): void
    {
        $input = AuthenticateInput::fromArray([
            'email' => null,
            'password' => null,
            'grant_type' => 'refresh',
            'refresh_token' => 'valid-refresh-token',
        ]);

        $user = $this->buildUser();
        $session = new Session('old-access', 'valid-refresh-token');

        $this->tokenManager
            ->expects($this->once())
            ->method('validate')
            ->with('valid-refresh-token')
            ->willReturn('uuid-123');

        $this->userRepository
            ->expects($this->once())
            ->method('find')
            ->with('uuid-123')
            ->willReturn($user);

        $this->tokenMemory
            ->expects($this->once())
            ->method('getSession')
            ->with('uuid-123')
            ->willReturn($session);

        $this->tokenManager
            ->expects($this->exactly(2))
            ->method('generate')
            ->willReturnOnConsecutiveCalls(
                new Token('new-refresh-token', 604800),
                new Token('new-access-token', 900),
            );

        $this->tokenMemory
            ->expects($this->once())
            ->method('setSessions');

        $output = $this->useCase->execute($input);

        $this->assertEquals(201, $output->getCode());
    }

    public function testAuthenticateInvalidPassword(): void
    {
        $input = AuthenticateInput::fromArray([
            'email' => 'john@example.com',
            'password' => 'wrong-password',
            'grant_type' => 'token',
        ]);

        $user = $this->buildUser();

        $this->userRepository
            ->expects($this->once())
            ->method('findByEmail')
            ->willReturn($user);

        $this->passwordEncoder
            ->expects($this->once())
            ->method('verify')
            ->willThrowException(new InvalidParamsException('Invalid password'));

        $output = $this->useCase->execute($input);

        $this->assertEquals(400, $output->getCode());
    }

    public function testAuthenticateInvalidInput(): void
    {
        $this->expectException(InvalidParamsException::class);

        AuthenticateInput::fromArray([
            'email' => 'test@test.com',
            'password' => 'pass',
            'grant_type' => 'invalid_type',
        ]);
    }
}
