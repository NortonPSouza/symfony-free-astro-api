<?php

namespace App\App\UseCase\AuthenticateUser\Input;

use App\App\UseCase\AuthenticateUser\Validation\AuthenticateUserCreateValidation;
use App\Domain\Exceptions\InvalidParamsException;

readonly class AuthenticateUserInput
{

    /**
     * @throws InvalidParamsException
     */
    public function __construct(
        private ?string $email,
        private ?string $password,
        private ?string $grant_type
    )
    {
        try {
            AuthenticateUserCreateValidation::validate($this->toArray());
        } catch (\Exception $exception) {
            throw new InvalidParamsException($exception->getMessage());
        }
    }

    /**
     * @throws InvalidParamsException
     */
    public static function fromArray(array $inputRequest): AuthenticateUserInput
    {
        return new AuthenticateUserInput(
            $inputRequest['email'] ?? null,
            $inputRequest['password'] ?? null,
            $inputRequest['grant_type'] ?? null
        );
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getGrantType(): ?string
    {
        return $this->grant_type;
    }

    public function toArray(): array
    {
        return [
            'email' => $this->getEmail(),
            'password' => $this->getPassword(),
            'grant_type' => $this->getGrantType()
        ];
    }
}