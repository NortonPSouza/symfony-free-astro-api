<?php

namespace App\App\UseCase\User\Delete\Input;

readonly class DeleteUserInput
{

    public function __construct(
        private string $id
    )
    {
    }

    static public function fromArray(string $userId): DeleteUserInput
    {
        return new DeleteUserInput($userId);
    }

    public function getId(): string
    {
        return $this->id;
    }

}