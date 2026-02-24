<?php

namespace App\Domain\Exceptions;

class NotFoundException extends GenericException
{
    public function __construct(string $message)
    {
        parent::__construct(404, $message);
    }
}