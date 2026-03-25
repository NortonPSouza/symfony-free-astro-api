<?php

namespace App\Domain\Exceptions;

class PdfGenerationException extends GenericException
{

    public function __construct(string $message)
    {
        parent::__construct(500, $message);
    }
}