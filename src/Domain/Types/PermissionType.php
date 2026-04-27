<?php

namespace App\Domain\Types;

enum PermissionType: int
{
    case PUBLISH_HOROSCOPE = 1;
    case COMMON_USER = 2;

    public function getType(): int
    {
        return $this->value;
    }
}
