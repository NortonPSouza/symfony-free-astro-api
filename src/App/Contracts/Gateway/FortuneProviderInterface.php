<?php

namespace App\App\Contracts\Gateway;

interface FortuneProviderInterface
{
    public function getRandomFortune(): string;
}
