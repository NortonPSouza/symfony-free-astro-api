<?php

namespace App\App\UseCase\User\Create\Output;

use App\App\UseCase\Shared\GenericOutput;

class CreateUserOutput extends GenericOutput
{

    public function __construct(
        string $title,
        string $path,
        array $data,
        int $code,
    )
    {
        parent::__construct($title, $path, $data, $code);
    }

    public static function success(array $output): CreateUserOutput
    {
        return new CreateUserOutput(
            'Created',
            '/api/v1/user',
            $output,
            201,
        );
    }

    public static function failure(int $code, array $output): CreateUserOutput
    {
        return new CreateUserOutput(
            'Error',
            '/api/v1/user',
            $output,
            $code,
        );
    }
}