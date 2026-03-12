<?php

namespace App\App\Contracts;

interface PasswordEncoderInterface
{
    public function encode(string $password): string;
    public function verify(string $password, string $hashedPassword): bool;
}