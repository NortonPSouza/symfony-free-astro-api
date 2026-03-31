<?php

namespace App\Infra\Adapters\Memory;

use App\App\Contracts\Database\MemoryInterface;
use App\App\Contracts\Memory\TokenMemoryInterface;
use App\Domain\Entity\Session;

readonly class TokenRedis implements TokenMemoryInterface
{

    public function __construct(
        private MemoryInterface $memory
    )
    {
    }

    public function setSessions(string $key, array $payload, int $ttl): void
    {
        $payload = json_encode($payload);
        $this->memory->set($key, $payload, $ttl);
    }

    public function getSession(string $key): Session
    {
       $tokenMemory = json_decode($this->memory->get($key), true);
       return new Session(
           $tokenMemory['access_token'],
           $tokenMemory['refresh_token']
       );
    }
}