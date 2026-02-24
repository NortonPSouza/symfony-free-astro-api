<?php

namespace App\Domain\Exceptions;

class RepositoryException extends GenericException
{
    public function __construct(string $message)
    {
        parent::__construct(500, $message);
    }
}