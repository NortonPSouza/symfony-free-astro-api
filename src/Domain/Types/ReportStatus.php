<?php

namespace App\Domain\Types;

enum ReportStatus: int
{
    case PENDING = 1;
    case PROCESSING = 2;
    case COMPLETED = 3;
    case FAILURE = 4;

    public function getStatus(): int
    {
        return $this->value;
    }
}
