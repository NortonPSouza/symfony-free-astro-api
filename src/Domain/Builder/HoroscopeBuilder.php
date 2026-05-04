<?php

namespace App\Domain\Builder;

use App\Domain\Entity\Horoscope;
use App\Domain\Entity\Zodiac;

class HoroscopeBuilder
{
    private ?string $id = null;
    private \DateTime $startDate;
    private \DateTime $endDate;
    private string $message;
    private int $luckNumber;
    private Zodiac $zodiac;

    public function withId(string $id): HoroscopeBuilder
    {
        $this->id = $id;
        return $this;
    }

    public function withStartDate(\DateTime $startDate): HoroscopeBuilder
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function withEndDate(\DateTime $endDate): HoroscopeBuilder
    {
        $this->endDate = $endDate;
        return $this;
    }

    public function withMessage(string $message): HoroscopeBuilder
    {
        $this->message = $message;
        return $this;
    }

    public function withLuckNumber(int $luckNumber): HoroscopeBuilder
    {
        $this->luckNumber = $luckNumber;
        return $this;
    }

    public function withZodiac(Zodiac $zodiac): HoroscopeBuilder
    {
        $this->zodiac = $zodiac;
        return $this;
    }

    public function build(): Horoscope
    {
        return new Horoscope(
            $this->id,
            $this->startDate,
            $this->endDate,
            $this->message,
            $this->luckNumber,
            $this->zodiac
        );
    }
}
