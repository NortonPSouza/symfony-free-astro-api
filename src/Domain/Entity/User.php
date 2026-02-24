<?php

namespace App\Domain\Entity;

use App\App\Contracts\ArraySerializationInterface;
use App\App\UseCase\User\Create\Input\InputCreateUser;

class User implements ArraySerializationInterface
{

    public function __construct(
        public ?int $id,
        public string $name,
        public string $familyName,
        public \DateTime $birthDate,
        public \DateTime $birthTime
    )
    {
    }

    static function create( InputCreateUser $user): User
    {
        return new User(null, "", "", new \DateTime(), new \DateTime());
    }

    public function toArray(): array
    {
        return [
            "name" => $this->name,
            "family_name" => $this->familyName,
            "birth_date" => $this->birthDate->format('Y-m-d'),
            "birth_time" => $this->birthTime->format('H:i:s')
        ];
    }
}