<?php

namespace App\Infra\Adapters\Mappers;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'user')]
class User
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'integer', length: 8)]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[ORM\Column(name: 'name', type: 'string',  length: 60, nullable: false)]
    private string $name;

    #[ORM\Column(name: 'family_name', type: 'string', length: 60, nullable: false)]
    private string $familyName;

    #[ORM\Column(name: 'birth_date', type: 'date', nullable: false)]
    private \DateTime $birthDate;

    #[ORM\Column(name: 'birth_time', type: 'time', nullable: true)]
    private ?\DateTime $birthTime;

    #[ORM\ManyToOne(targetEntity: Zodiac::class)]
    #[ORM\JoinColumn(name: 'zodiac_id', referencedColumnName: 'id', nullable: true)]
    private ?Zodiac $zodiac;

    public function __construct()
    {
        $this->setBirthTime(null);
        $this->setZodiac(null);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): User
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): User
    {
        $this->name = $name;
        return $this;
    }

    public function getFamilyName(): string
    {
        return $this->familyName;
    }

    public function setFamilyName(string $familyName): User
    {
        $this->familyName = $familyName;
        return $this;
    }

    public function getBirthDate(): \DateTime
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTime $birthDate): User
    {
        $this->birthDate = $birthDate;
        return $this;
    }

    public function getBirthTime(): ?\DateTime
    {
        return $this->birthTime;
    }

    public function setBirthTime(?\DateTime $birthTime): User
    {
        $this->birthTime = $birthTime;
        return $this;
    }

    public function getZodiac(): ?Zodiac
    {
        return $this->zodiac;
    }

    public function setZodiac(?Zodiac $zodiac): User
    {
        $this->zodiac = $zodiac;
        return $this;
    }

}