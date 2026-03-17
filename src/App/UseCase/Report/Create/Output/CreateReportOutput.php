<?php

namespace App\App\UseCase\Report\Create\Output;

use App\App\UseCase\Shared\GenericOutput;

class CreateReportOutput extends GenericOutput
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

    public static function success(array $output): CreateReportOutput
    {
        return new CreateReportOutput(
            'Created',
            '/api/v1/report',
            $output,
            201,
        );
    }

    public static function failure(int $code, array $output): CreateReportOutput
    {
        return new CreateReportOutput(
            'Error',
            '/api/v1/report',
            $output,
            $code,
        );
    }
}