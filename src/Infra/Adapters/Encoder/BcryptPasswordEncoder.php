<?php

namespace App\Infra\Adapters\Encoder;

use App\App\Contracts\Validation\PasswordEncoderInterface;

final class BcryptPasswordEncoder implements PasswordEncoderInterface
{
    public function encode(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function verify(string $password, string $hashedPassword): bool
    {
        return password_verify($password, $hashedPassword);
    }
}