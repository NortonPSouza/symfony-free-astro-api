<?php

namespace App\Domain\Exceptions;

class EventException extends GenericException
{
    public function __construct(string $message)
    {
        parent::__construct(502, $message);
    }
}