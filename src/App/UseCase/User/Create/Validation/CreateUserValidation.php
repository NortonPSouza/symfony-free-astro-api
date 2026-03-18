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
            ->key('familyName', v::stringType()->notEmpty()->setName('familyName'))
            ->key('birthDate', v::dateTime()->notEmpty()->setName('birthDate'));
        try {
            $validator->assert($inputRequest);
        } catch (NestedValidationException $exception) {
            throw new \Exception(json_encode($exception->getMessages()));
        }
    }
}