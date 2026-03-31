<?php

namespace App\App\Contracts\Validation;

use App\Domain\Exceptions\InvalidParamsException;

interface PasswordEncoderInterface
{
    public function encode(string $password): string;

    /**
     *  @throws InvalidParamsException
     */
    public function verify(string $password, string $hashedPassword): void;
}