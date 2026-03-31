<?php

namespace App\App\Contracts\Database;

interface MemoryInterface
{
    public function get(string $key): ?string;
    public function set(string $key, string $value, int $ttl): void;
    public function delete(string $key): void;
    public function exists(string $key): bool;

}
