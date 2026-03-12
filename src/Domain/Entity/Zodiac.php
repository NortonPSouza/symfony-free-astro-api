<?php

namespace App\Domain\Entity;

class Zodiac
{

    public function __construct(
        private int $id,
        private string $sign
    )
    {
    }

    static function create(int $id, string $sign): Zodiac
    {
        return new Zodiac($id, $sign);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getSign(): string
    {
        return $this->sign;
    }


}