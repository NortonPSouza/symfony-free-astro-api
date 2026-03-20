<?php

namespace App\App\UseCase\User\Create\Validation;

use App\App\Contracts\Validation\ValidationInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class CreateUserValidation implements ValidationInterface
{
    /**
     * @throws \Exception
     */
    public static function validate(array $inputRequest): void
    {
        $validator = v::create()
            ->key('name', v::stringType()->notEmpty()->setName('name'))
            ->key('family_name', v::stringType()->notEmpty()->setName('family_name'))
            ->key('birth_date', v::dateTime()->notEmpty()->setName('birth_date'))
            ->key('password', v::stringType()->notEmpty()->setName('password'))
            ->key('email', v::email()->notEmpty()->setName('email'));
        try {
            $validator->assert($inputRequest);
        } catch (NestedValidationException $exception) {
            throw new \Exception(json_encode($exception->getMessages()));
        }
    }
}