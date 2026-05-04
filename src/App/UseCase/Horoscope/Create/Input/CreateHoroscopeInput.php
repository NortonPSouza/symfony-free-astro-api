<?php

namespace App\App\UseCase\Horoscope\Create\Input;

use App\App\Contracts\Validation\ArraySerializationInterface;
use App\App\UseCase\Horoscope\Create\Validation\CreateHoroscopeValidation;
use App\Domain\Exceptions\InvalidParamsException;

readonly class CreateHoroscopeInput implements ArraySerializationInterface
{

    /**
     * @throws InvalidParamsException
     */
    public function __construct(
        private array $messages,
        private \DateTime $starDate,
        private \DateTime $endDate
    )
    {
        try {
            CreateHoroscopeValidation::validate($this->toArray());
        } catch (\Exception $exception) {
            throw new InvalidParamsException($exception->getMessage());
        }
    }

    static function fromArray(array $messages, \DateTime $starDate, \DateTime $endDate): CreateHoroscopeInput
    {
        return new CreateHoroscopeInput($messages, $starDate, $endDate);
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function getStarDate(): \DateTime
    {
        return $this->starDate;
    }

    public function getEndDate(): \DateTime
    {
        return $this->endDate;
    }

    public function toArray(): array
    {
        return [
            'messages' => $this->messages,
            'start_date' => $this->starDate->format('Y-m-d'),
            'end_date' => $this->endDate->format('Y-m-d'),
        ];
    }
}