<?php

namespace App\Domain\ValueObjects;

use App\Domain\Exceptions\InvalidParamsException;

readonly class Password
{
    private function __construct(private string $value) {}

    public static function create(string $password): Password
    {
        if (strlen($password) < 6) {
            throw new InvalidParamsException("Password must be at least 6 characters");
        }
        return new Password($password);
    }

    public static function fromHash(string $hash): Password
    {
        return new Password($hash);
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
