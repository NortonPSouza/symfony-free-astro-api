<?php

namespace App\Domain\ValueObjects;

readonly class Token
{
    public function __construct(
        private string $token,
        private int $expiresIn,
    )
    {
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }
}