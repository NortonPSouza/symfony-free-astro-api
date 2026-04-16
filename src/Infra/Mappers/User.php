<?php

namespace App\Infra\Mappers;
use App\Domain\Entity\User as UserDomain;
use App\Domain\ValueObjects\Email;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

#[ORM\Entity]
#[ORM\Table(name: 'user')]
#[Index(name: "email_login_idx", columns: ["email"])]
class User
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private string $id;

    #[ORM\Column(name: 'name', type: 'string',  length: 60, nullable: false)]
    private string $name;

    #[ORM\Column(name: 'email', type: 'string', length: 255, unique: true, nullable: false)]
    private string $email;

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

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): User
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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): User
    {
        $this->email = $email;
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

    public function toDomain(): UserDomain
    {
        $zodiac = $this->getZodiac();
        return new UserDomain(
            $this->getId(),
            $this->getName(),
            $this->getFamilyName(),
            Email::create($this->getEmail()),
            null,
            $this->getBirthDate(),
            $this->getBirthTime(),
            $zodiac ? \App\Domain\Entity\Zodiac::create($zodiac->getId(), $zodiac->getSign()) : null
        );
    }

}