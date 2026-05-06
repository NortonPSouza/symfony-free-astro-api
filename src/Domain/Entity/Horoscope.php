<?php

namespace App\Domain\Entity;

readonly class Horoscope
{

    public function __construct(
        private ?string $id,
        private ?\DateTime $startDate,
        private ?\DateTime $endDate,
        private ?string $message,
        private ?int $luckNumber,
        private ?Zodiac $zodiac
    )
    {
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function getLuckNumber(): ?int
    {
        return $this->luckNumber;
    }

    public function getZodiac(): ?Zodiac
    {
        return $this->zodiac;
    }

    public static function fromPrimitives(
        ?string $id,
        \DateTime $startDate,
        \DateTime $endDate,
        string $message,
        int $luckNumber,
        Zodiac $zodiac
    ): Horoscope
    {
        return new Horoscope(
            $id,
            $startDate,
            $endDate,
            $message,
            $luckNumber,
            $zodiac
        );
    }
}