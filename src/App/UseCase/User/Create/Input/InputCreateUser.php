<?php

namespace App\App\UseCase\User\Create\Input;

readonly class InputCreateUser
{

    public function __construct(
        private string $name,
        private string $familyName,
        private \DateTime $birthDate,
        private \DateTime $birthTime
    )
    {
    }

    /**
     * @throws \DateMalformedStringException
     */
    static function fromArray(array $inputRequest): InputCreateUser
    {
        return new InputCreateUser(
            $inputRequest['name'],
            $inputRequest['familyName'],
            new \DateTime($inputRequest['birthDate']),
            new \DateTime($inputRequest['birthTime'])
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFamilyName(): string
    {
        return $this->familyName;
    }

    public function getBirthDate(): \DateTime
    {
        return $this->birthDate;
    }

    public function getBirthTime(): \DateTime
    {
        return $this->birthTime;
    }


}