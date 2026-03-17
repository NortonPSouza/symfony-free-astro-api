<?php

namespace App\App\Contracts\Repository;

use App\Domain\Entity\Report;
use App\Domain\Exceptions\NotFoundException;
use Doctrine\ORM\Exception\ORMException;

interface ReportRepositoryInterface
{

    /**
     * @param Report $report
     * @return array
     * @throws ORMException|NotFoundException
     */
    public function create(Report $report): array;

}