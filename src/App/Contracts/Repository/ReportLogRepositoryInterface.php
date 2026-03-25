<?php

namespace App\App\Contracts\Repository;

use App\Domain\Entity\Report;

interface ReportLogRepositoryInterface
{

    public function save(Report $report): void;
}