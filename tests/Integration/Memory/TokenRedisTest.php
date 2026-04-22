<?php

namespace App\Tests\Integration\Memory;

use App\Infra\Adapters\Database\ConnectionRedis;
use App\Infra\Adapters\Memory\TokenRedis;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TokenRedisTest extends KernelTestCase
{
    private TokenRedis $tokenRedis;
    private ConnectionRedis $memory;
    private array $keys = [];

    protected function setUp(): void
    {
        self::bootKernel();
        $this->memory = new ConnectionRedis();
        $this->tokenRedis = new TokenRedis($this->memory);
    }

    protected function tearDown(): void
    {
        foreach ($this->keys as $key) {
            $this->memory->delete($key);
        }
        parent::tearDown();
    }

    public function testSetAndGetSession(): void
    {
        $this->keys[] = 'test-user-id';
        $payload = [
            'access_token' => 'test-access-token',
            'refresh_token' => 'test-refresh-token',
            'token_type' => 'Bearer'
        ];
        $this->tokenRedis->setSessions('test-user-id', $payload, 60);
        $session = $this->tokenRedis->getSession('test-user-id');
        $this->assertEquals('test-access-token', $session->getAccessToken());
        $this->assertEquals('test-refresh-token', $session->getRefreshToken());
        $this->assertEquals('Bearer', $session->getTokenType());
    }

    public function testOverwriteSession(): void
    {
        $this->keys[] = 'overwrite-user';
        $payload = [
            'access_token' => 'old-token',
            'refresh_token' => 'old-refresh',
            'token_type' => 'Bearer'
        ];
        $this->tokenRedis->setSessions('overwrite-user', $payload, 60);
        $newPayload = [
            'access_token' => 'new-token',
            'refresh_token' => 'new-refresh',
            'token_type' => 'Bearer'
        ];
        $this->tokenRedis->setSessions('overwrite-user', $newPayload, 60);
        $session = $this->tokenRedis->getSession('overwrite-user');
        $this->assertEquals('new-token', $session->getAccessToken());
        $this->assertEquals('new-refresh', $session->getRefreshToken());
    }
}
