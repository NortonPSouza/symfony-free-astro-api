<?php

namespace App\Tests\Integration\Gateway;

use App\Domain\Entity\User;
use App\Domain\Exceptions\UnauthorizedException;
use App\Domain\ValueObjects\Email;
use App\Infra\Adapters\Gateway\JwtManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class JwtManagerTest extends KernelTestCase
{
    private JwtManager $jwtManager;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->jwtManager = new JwtManager();
    }

    private function buildUser(): User
    {
        return new User(
            'uuid-123',
            'John',
            'Doe',
            Email::create('john@example.com'),
            null,
            new \DateTime('1990-05-15'),
            null,
            null
        );
    }

    public function testGenerateAndValidateToken(): void
    {
        $user = $this->buildUser();
        $token = $this->jwtManager->generate($user, 900);
        $this->assertNotEmpty($token->getToken());
        $this->assertEquals(900, $token->getExpiresIn());
        $userId = $this->jwtManager->validate($token->getToken());
        $this->assertEquals('uuid-123', $userId);
    }

    public function testValidateInvalidToken(): void
    {
        $this->expectException(UnauthorizedException::class);
        $this->jwtManager->validate('invalid.token.here');
    }

    public function testValidateExpiredToken(): void
    {
        $user = $this->buildUser();
        $token = $this->jwtManager->generate($user, -1);
        $this->expectException(UnauthorizedException::class);
        $this->jwtManager->validate($token->getToken());
    }
}
