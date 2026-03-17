<?php

namespace App\App\Contracts\Repository;

use App\Infra\Adapters\Mappers\Zodiac;

interface ZodiacRepositoryInterface
{
    public function getSignByBirth(\DateTime $birth): Zodiac;
}