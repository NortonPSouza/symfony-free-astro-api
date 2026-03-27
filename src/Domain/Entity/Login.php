<?php

namespace App\Domain\Entity;

use App\App\Contracts\Validation\ArraySerializationInterface;
use App\App\UseCase\AuthenticateUser\Input\AuthenticateUserInput;

class Login implements ArraySerializationInterface
{

    public function __construct(
        private string $id,
        private string $email,
        private string $password,
        private ?string $refreshToken,
        private ?\DateTime $expiresIn
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

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(?string $refreshToken): Login
    {
        $this->refreshToken = $refreshToken;
        return $this;
    }

    public function getExpiresIn(): ?\DateTime
    {
        return $this->expiresIn;
    }

    public function setExpiresIn(?\DateTime $expiresIn): Login
    {
        $this->expiresIn = $expiresIn;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'email' => $this->getEmail(),
            'password' => $this->getPassword(),
            'refreshToken' => $this->getRefreshToken(),
            'expiresIn' => $this->getExpiresIn()->format('Y-m-d H:i:s')
        ];
    }
}