<?php

namespace App\Infra\Mappers;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'horoscope')]
class Horoscope
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private string $id;

    #[ORM\Column(name: 'start_date', type: 'date', nullable: false)]
    private \DateTime $startDate;

    #[ORM\Column(name: 'end_date', type: 'date', nullable: false)]
    private \DateTime $endDate;

    #[ORM\Column(name: 'message', type: 'string', length: 255, nullable: false)]
    private string $message;

    #[ORM\Column(name: 'luck_number', type: 'integer', nullable: false)]
    private int $luckNumber;

    #[ORM\Column(name: 'published', type: 'boolean', options: ['default' => false])]
    private bool $published;

    #[ORM\ManyToOne(targetEntity: Zodiac::class)]
    #[ORM\JoinColumn(name: 'zodiac_id', referencedColumnName: 'id', nullable: false)]
    private Zodiac $zodiac;

    public function __construct()
    {
        $this->setPublished(false);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): Horoscope
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

    public function getEndDate(): \DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTime $endDate): Horoscope
    {
        $this->endDate = $endDate;
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

    public function isPublished(): bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): Horoscope
    {
        $this->published = $published;
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