<?php

namespace App\Domain\Exceptions;

abstract class GenericException extends \Exception
{
    private array $data;

    public function __construct(
        protected readonly int $statusCode,
        string $message
    )
    {
        parent::__construct($message);
        $this->data = json_decode($message, true) ?? ['exception' => $message];
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getData(): array
    {
        return $this->data;
    }

}