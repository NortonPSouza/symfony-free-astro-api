<?php

namespace App\App\Contracts;

interface ValidationInterface
{
    public static function validate(array $inputRequest): void;
}