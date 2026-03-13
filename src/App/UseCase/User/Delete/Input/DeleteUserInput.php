<?php

namespace App\App\UseCase\User\Delete\Input;

readonly class DeleteUserInput
{

    public function __construct(
        private int $id
    )
    {
    }

    static public function fromArray(int $userId): DeleteUserInput
    {
        return new DeleteUserInput($userId);
    }

    public function getId(): int
    {
        return $this->id;
    }

}