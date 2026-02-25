<?php

namespace App\App\UseCase\User\Create\Input;

use App\App\Contracts\ArraySerializationInterface;
use App\App\UseCase\User\Create\CreateUserValidation;
use App\Domain\Exceptions\InvalidParamsException;

readonly class CreateUserInput implements ArraySerializationInterface
{

    /**
     * @throws InvalidParamsException
     */
    public function __construct(
        private string $name,
        private string $familyName,
        private \DateTime $birthDate,
        private ?\DateTime $birthTime,
    )
    {
        try {
            CreateUserValidation::validate($this->toArray());
        } catch (\Exception $exception) {
            throw new InvalidParamsException($exception->getMessage());
        }
    }

    /**
     * @throws \DateMalformedStringException|InvalidParamsException
     */
    static function fromArray(array $inputRequest): CreateUserInput
    {
        return new CreateUserInput(
            $inputRequest['name'],
            $inputRequest['familyName'],
            new \DateTime($inputRequest['birthDate']),
            new \DateTime($inputRequest['birthTime']) ?? null
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