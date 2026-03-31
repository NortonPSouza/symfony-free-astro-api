<?php

namespace App\App\Contracts\Memory;

use App\Domain\Entity\Session;

interface TokenMemoryInterface
{
    public function setSessions(string $key, array $payload, int $ttl): void;
    public function getSession(string $key): Session;
}