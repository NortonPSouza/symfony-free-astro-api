<?php

namespace App\App\UseCase\Horoscope\Find\Input;

readonly class FindHoroscopeInput
{
    public function __construct(
        private string $zodiacId
    )
    {
    }

    public static function fromArray(string $zodiacId): FindHoroscopeInput
    {
        return new FindHoroscopeInput($zodiacId);
    }

    public function getZodiacId(): string
    {
        return $this->zodiacId;
    }
}
