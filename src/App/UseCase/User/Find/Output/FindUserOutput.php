<?php

namespace App\App\UseCase\User\Find\Output;

use App\App\UseCase\Shared\GenericOutput;

class FindUserOutput extends GenericOutput
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

    public static function success(array $output): FindUserOutput
    {
        return new FindUserOutput(
            'Created',
            '/api/v1/user/{user_id}',
            $output,
            200,
        );
    }

    public static function failure(int $code, array $output): FindUserOutput
    {
        return new FindUserOutput(
            'Error',
            '/api/v1/user/{user_id}',
            $output,
            $code,
        );
    }
}