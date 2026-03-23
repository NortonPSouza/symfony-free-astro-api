<?php

namespace App\App\UseCase\Report\Generate\Input;

readonly class GenerateReportInput
{
    public function __construct(
        private string $processId
    )
    {
    }

    public function getProcessId(): string
    {
        return $this->processId;
    }
}