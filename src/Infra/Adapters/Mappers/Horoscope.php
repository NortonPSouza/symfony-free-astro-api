<?php

namespace App\Infra\Adapters\Mappers;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'horoscope')]
class Horoscope
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'integer', length: 8)]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[ORM\Column(name: 'start_date', type: 'date', nullable: false)]
    private \DateTime $startDate;

    #[ORM\Column(name: 'message', type: 'string', length: 255, nullable: false)]
    private string $message;

    #[ORM\Column(name: 'luck_number', type: 'integer', nullable: false)]
    private int $luckNumber;

    #[ORM\ManyToOne(targetEntity: Zodiac::class)]
    #[ORM\JoinColumn(name: 'zodiac_id', referencedColumnName: 'id', nullable: false)]
    private Zodiac $zodiac;


    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Horoscope
    {
        $this->id = $id;
        return $this;
    }

    public function getStartDate(): \DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTime $startDate): Horoscope
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): Horoscope
    {
        $this->message = $message;
        return $this;
    }

    public function getLuckNumber(): int
    {
        return $this->luckNumber;
    }

    public function setLuckNumber(int $luckNumber): Horoscope
    {
        $this->luckNumber = $luckNumber;
        return $this;
    }

    public function getZodiac(): Zodiac
    {
        return $this->zodiac;
    }

    public function setZodiac(Zodiac $zodiac): Horoscope
    {
        $this->zodiac = $zodiac;
        return $this;
    }

}