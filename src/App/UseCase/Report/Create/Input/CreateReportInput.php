<?php

namespace App\App\UseCase\Report\Create\Input;

use App\App\Contracts\Validation\ArraySerializationInterface;
use App\App\UseCase\Report\Create\Validation\CreateReportValidation;
use App\Domain\Exceptions\InvalidParamsException;

readonly class CreateReportInput implements ArraySerializationInterface
{

    /**
     * @throws InvalidParamsException
     */
    public function __construct(
        private int $userId,
        private int $month,
        private int $year,
    )
    {
        try {
            CreateReportValidation::validate($this->toArray());
        } catch (\Exception $exception) {
            throw new InvalidParamsException($exception->getMessage());
        }
    }

    /**
     * @throws InvalidParamsException
     */
    static function fromArray(array $inputRequest): CreateReportInput
    {
        return new CreateReportInput(
            (int) $inputRequest['user_id'],
            (int) $inputRequest['month'],
            (int) $inputRequest['year'],
        );
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getMonth(): int
    {
        return $this->month;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->getUserId(),
            'month' => $this->getMonth(),
            'year' => $this->getYear(),
        ];
    }
}