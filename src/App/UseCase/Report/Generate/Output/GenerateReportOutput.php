<?php

namespace App\App\UseCase\Report\Generate\Output;

use App\App\UseCase\Shared\GenericOutput;

class GenerateReportOutput extends GenericOutput
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

    public static function success(array $output): GenerateReportOutput
    {
        return new GenerateReportOutput(
            'Created',
            '/api/v1/report/generate',
            $output,
            200,
        );
    }

    public static function failure(int $code, array $output): GenerateReportOutput
    {
        return new GenerateReportOutput(
            'Error',
            '/api/v1/report/generate',
            $output,
            $code,
        );
    }
}