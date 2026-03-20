<?php

namespace App\App\UseCase\User\Find\Input;

 readonly class FindUserInput
{

    public function __construct(
        private string $id
    )
    {
    }

     static public function fromArray(string $userId): FindUserInput
    {
      return new FindUserInput($userId);
    }

     public function getId(): string
     {
         return $this->id;
     }


 }