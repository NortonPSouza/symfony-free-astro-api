<?php

namespace App\Infra\Adapters\Repository;

use App\App\Contracts\Repository\FortuneProviderInterface;

readonly class JsonFortuneProvider implements FortuneProviderInterface
{

    public function getRandomFortune(): string
    {
        $path = __DIR__ . '/../../../../local/fortune-message.json';
        $data = json_decode(file_get_contents($path), true);
        return $data[array_rand($data)];
    }
}