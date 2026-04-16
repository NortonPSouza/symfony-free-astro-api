<?php

namespace App\App\Factory;

use App\App\UseCase\User\Create\Input\CreateUserInput;
use App\Domain\Entity\User;
use App\Domain\ValueObjects\Email;
use App\Domain\ValueObjects\Password;

abstract class UserFactory
{
    public static function fromInput(CreateUserInput $input): User
    {
        return new User(
            null,
            $input->getName(),
            $input->getFamilyName(),
            Email::create($input->getEmail()),
            Password::create($input->getPassword()),
            $input->getBirthDate(),
            $input->getBirthTime(),
            null
        );
    }
}