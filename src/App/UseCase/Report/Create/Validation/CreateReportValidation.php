<?php

namespace App\App\UseCase\Report\Create\Validation;

use App\App\Contracts\ValidationInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class CreateReportValidation implements ValidationInterface
{
    /**
     * @throws \Exception
     */
    public static function validate(array $inputRequest): void
    {
        $validator = v::create()
            ->key('user_id', v::intType()->notEmpty()->setName('user_id'))
            ->key('month', v::notEmpty()->intVal()->between(1, 12)->setName('month'))
            ->key('year', v::notEmpty()->digit()->length(4, 4)->setName('year'));
        try {
            $validator->assert($inputRequest);
        } catch (NestedValidationException $exception) {
            throw new \Exception(json_encode($exception->getMessages()));
        }
    }
}