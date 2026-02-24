<?php

namespace App\Domain\Exceptions;

class ForbiddenException extends GenericException
{
    public function __construct(string $message)
    {
        parent::__construct(403, $message);
    }
}