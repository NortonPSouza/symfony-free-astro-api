<?php

namespace App\Domain\Types;

enum LifeTimeToken: int
{
    case FIFTEEN_MINUTES = 900;
    case SEVEN_DAYS = 604800;

    public function getSeconds(): int
    {
        return $this->value;
    }
}
