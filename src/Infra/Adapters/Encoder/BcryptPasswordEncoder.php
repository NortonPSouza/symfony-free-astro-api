<?php

namespace App\Infra\Adapters\Encoder;

use App\App\Contracts\Validation\PasswordEncoderInterface;
use App\Domain\Exceptions\InvalidParamsException;

final class BcryptPasswordEncoder implements PasswordEncoderInterface
{
    public function encode(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function verify(string $password, string $hashedPassword): void
    {
        $isValid =  password_verify($password, $hashedPassword);
        if (!$isValid) {
            throw new InvalidParamsException('Invalid password');
        }
    }
}