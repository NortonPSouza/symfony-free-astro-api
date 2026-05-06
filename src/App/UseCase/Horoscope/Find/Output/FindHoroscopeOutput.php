<?php

namespace App\App\UseCase\Horoscope\Find\Output;

use App\App\UseCase\Shared\GenericOutput;

class FindHoroscopeOutput extends GenericOutput
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

    public static function success(array $output): FindHoroscopeOutput
    {
        return new FindHoroscopeOutput(
            'OK',
            '/api/v1/horoscope/{sign}',
            $output,
            200,
        );
    }

    public static function failure(int $code, array $output): FindHoroscopeOutput
    {
        return new FindHoroscopeOutput(
            'Error',
            '/api/v1/horoscope/{sign}',
            $output,
            $code,
        );
    }
}
