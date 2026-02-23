<?php

namespace App\Infra\Adapters\Mappers;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'login_user')]
class LoginUser
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'integer', length: 8)]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Login::class)]
    #[ORM\JoinColumn(name: 'login_id', referencedColumnName: 'id', nullable: false)]
    private Login $login;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    private User $user;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): LoginUser
    {
        $this->id = $id;
        return $this;
    }

    public function getLogin(): Login
    {
        return $this->login;
    }

    public function setLogin(Login $login): LoginUser
    {
        $this->login = $login;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): LoginUser
    {
        $this->user = $user;
        return $this;
    }

}