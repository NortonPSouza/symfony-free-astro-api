<?php

namespace App\App\UseCase\AuthenticateUser\Validation;

use App\App\Contracts\Validation\ValidationInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class AuthenticateUserCreateValidation implements ValidationInterface
{

    public static function validate(array $inputRequest): void
    {
        $validator = v::create()
            ->key('email', v::notEmpty()->email()->setName('email'))
            ->key('password', v::notEmpty()->stringType()->setName('password'));
        try {
            $validator->assert($inputRequest);
        } catch (NestedValidationException $exception) {
            throw new \Exception(json_encode($exception->getMessages()));
        }
    }
}