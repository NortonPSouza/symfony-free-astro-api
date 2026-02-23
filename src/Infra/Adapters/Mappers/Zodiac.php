<?php

namespace App\Infra\Adapters\Mappers;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'zodiac')]
class Zodiac
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'integer', length: 8)]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[ORM\Column(name: 'sign', type: 'string',  length: 60, nullable: false)]
    private string $sign;

     #[ORM\Column(name: 'start_date', type: 'date', nullable: false)]
     private \DateTime $startDate;

    #[ORM\Column(name: 'end_date', type: 'date', nullable: false)]
    private \DateTime $endDate;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Zodiac
    {
        $this->id = $id;
        return $this;
    }

    public function getSign(): string
    {
        return $this->sign;
    }

    public function setSign(string $sign): Zodiac
    {
        $this->sign = $sign;
        return $this;
    }

    public function getStartDate(): \DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTime $startDate): Zodiac
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getEndDate(): \DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTime $endDate): Zodiac
    {
        $this->endDate = $endDate;
        return $this;
    }

}