<?php

namespace App\Infra\Adapters\Mappers;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'zodiac')]
class Zodiac
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private string $id;

    #[ORM\Column(name: 'sign', type: 'string',  length: 60, nullable: false)]
    private string $sign;

     #[ORM\Column(name: 'start_date', type: 'date', nullable: false)]
     private \DateTime $startDate;

    #[ORM\Column(name: 'end_date', type: 'date', nullable: false)]
    private \DateTime $endDate;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): Zodiac
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