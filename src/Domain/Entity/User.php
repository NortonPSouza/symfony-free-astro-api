<?php

namespace App\Domain\Entity;

use App\App\Contracts\Validation\ArraySerializationInterface;
use App\Domain\ValueObjects\Email;
use App\Domain\ValueObjects\Password;

class User implements ArraySerializationInterface
{

    public function __construct(
        private readonly ?string $id,
        private readonly string $name,
        private readonly string $familyName,
        private readonly Email $email,
        private ?Password $password,
        private readonly \DateTime $birthDate,
        private readonly ?\DateTime $birthTime,
        private readonly ?Zodiac $zodiac
    )
    {
    }


    public function getId(): ?string
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

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPassword(): ?Password
    {
        return $this->password;
    }

    public function setPassword(Password $password): void
    {
        $this->password = $password;
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
            "id" => $this->getId(),
            "name" => $this->getName(),
            "family_name" => $this->getFamilyName(),
            "birth_date" => $this->getBirthDate(),
            "birth_time" => $this->getBirthTime()
        ];
    }

    public static function fromPrimitives(
        ?string $id,
        string $name,
        string $familyName,
        string $email,
        \DateTime $birthDate,
        ?\DateTime $birthTime = null,
        ?string $zodiacId = null,
        ?string $zodiacSign = null,
        ?Password $password = null
    ): User
    {
        $zodiac = $zodiacId && $zodiacSign
            ? Zodiac::create($zodiacId, $zodiacSign)
            : null;
        return new User($id, $name, $familyName, Email::create($email), $password, $birthDate, $birthTime, $zodiac);
    }
}