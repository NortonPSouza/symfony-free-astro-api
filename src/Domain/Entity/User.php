<?php

namespace App\Domain\Entity;

use App\App\Contracts\ArraySerializationInterface;
use App\App\Contracts\PasswordEncoderInterface;
use App\App\UseCase\User\Create\Input\CreateUserInput;

class User implements ArraySerializationInterface
{

    public function __construct(
        private ?int $id,
        private string $name,
        private string $familyName,
        private string $email,
        private string $password,
        private \DateTime $birthDate,
        private ?\DateTime $birthTime,
        private ?Zodiac $zodiac
    )
    {
    }

    static function create(CreateUserInput $user): User
    {
        return new User(
            null,
            $user->getName(),
            $user->getFamilyName(),
            $user->getEmail(),
            $user->getPassword(),
            $user->getBirthDate(),
            $user->getBirthTime(),
            null
        );
    }

    public function setZodiacSing(Zodiac $sing): void
    {
        $this->zodiac = $sing;
    }

    public function setEncryptedPassword(PasswordEncoderInterface $passwordEncoder): void
    {
        $this->password = $passwordEncoder->encode($this->password);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFamilyName(): string
    {
        return $this->familyName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getBirthDate(): \DateTime
    {
        return $this->birthDate;
    }

    public function getBirthTime(): ?\DateTime
    {
        return $this->birthTime;
    }

    public function getZodiac(): ?Zodiac
    {
        return $this->zodiac;
    }

    public function toArray(): array
    {
        return [
            "name" => $this->getName(),
            "family_name" => $this->getFamilyName(),
            "birth_date" => $this->getBirthDate(),
            "birth_time" => $this->getBirthTime()
        ];
    }
}