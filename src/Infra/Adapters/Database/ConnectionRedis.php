<?php

namespace App\Infra\Adapters\Database;

use App\App\Contracts\Database\CacheInterface;
use Predis\Client;

readonly class ConnectionRedis implements CacheInterface
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'scheme' => 'tcp',
            'host'   => $_ENV['REDIS_HOST'],
            'port'   => $_ENV['REDIS_PORT'],
        ]);
    }

    public function get(string $key): ?string
    {
        return $this->client->get($key);
    }

    public function set(string $key, string $value, int $ttl = 3600): void
    {
        $this->client->setex($key, $ttl, $value);
    }

    public function delete(string $key): void
    {
        $this->client->del([$key]);
    }

    public function exists(string $key): bool
    {
        return (bool) $this->client->exists($key);
    }
}
