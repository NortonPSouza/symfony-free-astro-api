<?php

namespace App\App\UseCase\User\Delete\Output;

use App\App\UseCase\Shared\GenericOutput;

class DeleteUserOutput extends GenericOutput
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

    public static function success(array $output): DeleteUserOutput
    {
        return new DeleteUserOutput(
            'Deleted',
            '/api/v1/user/{user_id}',
            $output,
            200,
        );
    }

    public static function failure(int $code, array $output): DeleteUserOutput
    {
        return new DeleteUserOutput(
            'Error',
            '/api/v1/user/{user_id}',
            $output,
            $code,
        );
    }
}