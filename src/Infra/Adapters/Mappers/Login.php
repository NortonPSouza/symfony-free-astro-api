<?php

namespace App\Infra\Adapters\Mappers;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'login')]
class Login
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'integer', length: 8)]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[ORM\Column(name: 'email', type: 'string', length: 255, unique: true, nullable: false)]
    private string $email;

    #[ORM\Column(name: 'password', type: 'string', length: 255, nullable: false)]
    private string $password;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Login
    {
        $this->id = $id;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): Login
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): Login
    {
        $this->password = $password;
        return $this;
    }

}