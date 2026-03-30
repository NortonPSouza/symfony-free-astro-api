<?php

namespace App\Domain\Entity;

use App\App\Contracts\Validation\ArraySerializationInterface;
use App\App\UseCase\AuthenticateUser\Input\AuthenticateUserInput;

readonly class Login implements ArraySerializationInterface
{

    public function __construct(
        private string $id,
        private string $email,
        private string $password
    )
    {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function toArray(): array
    {
        return [
            'email' => $this->getEmail(),
            'password' => $this->getPassword()
        ];
    }
}