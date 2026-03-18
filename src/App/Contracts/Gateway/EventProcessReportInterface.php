<?php

namespace App\App\Contracts\Gateway;

use App\Domain\Entity\Report;
use App\Domain\Exceptions\EventException;

interface EventProcessReportInterface
{
    /**
     * @param Report $report
     * @return void
     * @throws EventException
     *
     */
    public function execute(Report $report): void;
}