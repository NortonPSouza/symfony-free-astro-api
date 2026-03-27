<?php

namespace App\Domain\Exceptions;

class UnauthorizedException extends GenericException
{
    public function __construct(string $message)
    {
        parent::__construct(401, $message);
    }
}
