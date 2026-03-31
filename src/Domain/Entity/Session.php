<?php

namespace App\Domain\Entity;

use App\Domain\Exceptions\UnauthorizedException;

readonly class Session
{

    public function __construct(
        private string $accessToken,
        private string $refreshToken,
        private string $tokenType = 'Bearer'
    )
    {
    }

    /**
     * @throws UnauthorizedException
     */
    public function validateRefreshToken(string $refreshToken): void
    {
        if ($this->refreshToken !== $refreshToken) {
            throw new UnauthorizedException('invalid token');
        }
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function getTokenType(): string
    {
        return $this->tokenType;
    }


}