<?php

namespace App\Domain\Types;

enum GrantTypeLogin: string
{
    case TOKEN = 'token';
    case REFRESH_TOKEN = 'refresh_token';

    public function getType(): string
    {
        return $this->value;
    }

}
