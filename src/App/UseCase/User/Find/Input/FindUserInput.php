<?php

namespace App\App\UseCase\User\Find\Input;

 readonly class FindUserInput
{

    public function __construct(
        private int $id
    )
    {
    }

     static public function fromArray(int $userId): FindUserInput
    {
      return new FindUserInput($userId);
    }

     public function getId(): int
     {
         return $this->id;
     }


 }