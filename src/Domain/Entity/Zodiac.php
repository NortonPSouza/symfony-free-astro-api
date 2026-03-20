<?php

namespace App\Domain\Entity;

class Zodiac
{

    public function __construct(
        private string $id,
        private string $sign
    )
    {
    }

    static function create(string $id, string $sign): Zodiac
    {
        return new Zodiac($id, $sign);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getSign(): string
    {
        return $this->sign;
    }


}