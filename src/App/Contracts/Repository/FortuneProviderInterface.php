<?php

namespace App\App\Contracts\Repository;

interface FortuneProviderInterface
{
    public function getRandomFortune(): string;
}