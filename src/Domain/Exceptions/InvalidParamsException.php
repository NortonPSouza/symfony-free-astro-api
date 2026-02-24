<?php

namespace App\Domain\Exceptions;

class InvalidParamsException extends GenericException
{
    public function __construct(string $message)
    {
        parent::__construct(400, $message);
    }
}