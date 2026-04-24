<?php

namespace App\Infra\Mappers;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

#[ORM\Entity]
#[ORM\Table(name: 'login')]
#[Index(name: "email_login_idx", columns: ["email"])]
class Login
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private string $id;
    #[ORM\Column(name: 'email', type: 'string', length: 255, unique: true, nullable: false)]
    private string $email;

    #[ORM\Column(name: 'password', type: 'string', length: 255, nullable: false)]
    private string $password;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): Login
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