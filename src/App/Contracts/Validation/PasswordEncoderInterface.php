<?php

namespace App\App\Contracts\Validation;

interface PasswordEncoderInterface
{
    public function encode(string $password): string;
    public function verify(string $password, string $hashedPassword): bool;
}