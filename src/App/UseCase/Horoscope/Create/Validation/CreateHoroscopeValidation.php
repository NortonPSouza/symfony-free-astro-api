<?php

namespace App\App\UseCase\Horoscope\Create\Validation;

use App\App\Contracts\Validation\ValidationInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class CreateHoroscopeValidation implements ValidationInterface
{
    /**
     * @throws \Exception
     */
    public static function validate(array $inputRequest): void
    {
        $validator = v::create()
            ->key('messages', v::notEmpty()->arrayType()->length(12, 12)->setName('messages'))
            ->key('start_date', v::notEmpty()->date()->setName('start_date'))
            ->key('end_date', v::notEmpty()->date()->setName('end_date'));
        try {
            $validator->assert($inputRequest);
        } catch (NestedValidationException $exception) {
            throw new \Exception(json_encode($exception->getMessages()));
        }
    }
}