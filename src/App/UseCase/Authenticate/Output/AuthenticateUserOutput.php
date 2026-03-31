<?php

namespace App\App\UseCase\Authenticate\Output;

use App\App\UseCase\Shared\GenericOutput;

class AuthenticateUserOutput extends GenericOutput
{

    public function __construct(string $title, string $path, array $data, int $code)
    {
        parent::__construct($title, $path, $data, $code);
    }

    public static function success(array $output): AuthenticateUserOutput
    {
        return new AuthenticateUserOutput(
            'Created',
            '/api/v1/authenticate/user',
            $output,
            201,
        );
    }

    public static function failure(int $code, array $output): AuthenticateUserOutput
    {
        return new AuthenticateUserOutput(
            'Error',
            '/api/v1/authenticate/user',
            $output,
            $code,
        );
    }
}