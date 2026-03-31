<?php

namespace App\App\UseCase\Authenticate\Validation;

use App\App\Contracts\Validation\ValidationInterface;
use App\Domain\Exceptions\InvalidParamsException;
use App\Domain\Types\GrantTypeLogin;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class AuthenticateValidation implements ValidationInterface
{

    /**
     * @throws InvalidParamsException
     */
    public static function validate(array $inputRequest): void
    {
        $validator = v::create()->key(
            'grant_type', v::notEmpty()->stringType()->in(['token', 'refresh'])->setName('grant_type')
        );
        if ($inputRequest['grant_type'] === GrantTypeLogin::TOKEN->getType()) {
            $validator
                ->key('email', v::notEmpty()->email()->setName('email'))
                ->key('password', v::notEmpty()->stringType()->setName('password'));
        } else {
            $validator
                ->key('refresh_token', v::notEmpty()->stringType()->setName('refresh_token'));
        }
        try {
            $validator->assert($inputRequest);
        } catch (NestedValidationException $exception) {
            throw new InvalidParamsException(json_encode($exception->getMessages()));
        }
    }
}