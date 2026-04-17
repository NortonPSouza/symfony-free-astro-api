<?php

namespace App\Domain\Builder;

use App\App\Contracts\Validation\PasswordEncoderInterface;
use App\Domain\Entity\User;
use App\Domain\Entity\Zodiac;
use App\Domain\ValueObjects\Email;
use App\Domain\ValueObjects\Password;

class UserBuilder
{
    private ?string $id = null;
    private string $name;
    private string $familyName;
    private Email $email;
    private ?Password $password = null;
    private \DateTime $birthDate;
    private ?\DateTime $birthTime = null;
    private ?Zodiac $zodiac = null;

    public function withName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function withFamilyName(string $familyName): self
    {
        $this->familyName = $familyName;
        return $this;
    }

    public function withEmail(string $email): self
    {
        $this->email = Email::create($email);
        return $this;
    }

    public function withPassword(string $password): self
    {
        $this->password = Password::create($password);
        return $this;
    }

    public function withEncryptedPassword(PasswordEncoderInterface $encoder): self
    {
        $this->password = Password::fromHash($encoder->encode($this->password->getValue()));
        return $this;
    }

    public function withBirthDate(\DateTime $birthDate): self
    {
        $this->birthDate = $birthDate;
        return $this;
    }

    public function withBirthTime(?\DateTime $birthTime): self
    {
        $this->birthTime = $birthTime;
        return $this;
    }

    public function withZodiac(Zodiac $zodiac): self
    {
        $this->zodiac = $zodiac;
        return $this;
    }

    public function build(): User
    {
        return new User(
            $this->id,
            $this->name,
            $this->familyName,
            $this->email,
            $this->password,
            $this->birthDate,
            $this->birthTime,
            $this->zodiac
        );
    }
}
