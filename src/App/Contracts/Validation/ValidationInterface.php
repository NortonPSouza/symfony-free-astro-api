<?php

namespace App\App\Contracts\Validation;

interface ValidationInterface
{
    public static function validate(array $inputRequest): void;
}