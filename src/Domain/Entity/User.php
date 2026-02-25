<?php

namespace App\Domain\Entity;

use App\App\Contracts\ArraySerializationInterface;
use App\App\UseCase\User\Create\Input\CreateUserInput;
use App\Infra\Adapters\Mappers\Zodiac;

class User implements ArraySerializationInterface
{

    public function __construct(
        public ?int $id,
        public string $name,
        public string $familyName,
        public \DateTime $birthDate,
        public ?\DateTime $birthTime,
        public ?Zodiac $zodiac
    )
    {
    }

    static function create(CreateUserInput $user): User
    {
        return new User(
            null,
            $user->getName(),
            $user->getFamilyName(),
            $user->getBirthDate(),
            $user->getBirthTime(),
            null
        );
    }

    public function setZodiacSing(Zodiac $sing): void
    {
        $this->zodiac = $sing;
    }

    public function toArray(): array
    {
        return [
            "name" => $this->name,
            "family_name" => $this->familyName,
            "birth_date" => $this->birthDate,
            "birth_time" => $this->birthTime
        ];
    }
}