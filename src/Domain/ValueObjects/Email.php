<?php

namespace App\Domain\ValueObjects;

use App\Domain\Exceptions\InvalidParamsException;

readonly class Email
{
    private function __construct(private string $value) {}

    public static function create(string $email): Email
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidParamsException("Invalid email: {$email}");
        }
        return new Email($email);
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
