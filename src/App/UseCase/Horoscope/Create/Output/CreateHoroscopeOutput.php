<?php

namespace App\App\UseCase\Horoscope\Create\Output;

use App\App\UseCase\Shared\GenericOutput;

class CreateHoroscopeOutput extends GenericOutput
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

    public static function success(array $output): CreateHoroscopeOutput
    {
        return new CreateHoroscopeOutput(
            'Created',
            '/api/v1/horoscope',
            $output,
            201,
        );
    }

    public static function failure(int $code, array $output): CreateHoroscopeOutput
    {
        return new CreateHoroscopeOutput(
            'Error',
            '/api/v1/horoscope',
            $output,
            $code,
        );
    }
}