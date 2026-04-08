<?php

namespace App\App\UseCase\Authenticate\Input;

use App\App\UseCase\Authenticate\Validation\AuthenticateValidation;
use App\Domain\Exceptions\InvalidParamsException;

readonly class AuthenticateInput
{

    /**
     * @throws InvalidParamsException
     */
    public function __construct(
        private ?string $email,
        private ?string $password,
        private ?string $grantType,
        private ?string $refreshToken
    )
    {
        try {
            AuthenticateValidation::validate($this->toArray());
        } catch (\Exception $exception) {
            throw new InvalidParamsException($exception->getMessage());
        }
    }

    /**
     * @throws InvalidParamsException
     */
    public static function fromArray(array $inputRequest): AuthenticateInput
    {
        return new AuthenticateInput(
            $inputRequest['email'] ?? null,
            $inputRequest['password'] ?? null,
            $inputRequest['grant_type'] ?? null,
            $inputRequest['refresh_token'] ?? null
        );
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getGrantType(): ?string
    {
        return $this->grantType;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }


    public function toArray(): array
    {
        return [
            'email' => $this->getEmail(),
            'password' => $this->getPassword(),
            'grant_type' => $this->getGrantType(),
            'refresh_token' => $this->getRefreshToken()
        ];
    }
}