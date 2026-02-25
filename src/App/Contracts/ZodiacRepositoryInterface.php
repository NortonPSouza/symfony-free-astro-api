<?php

namespace App\App\Contracts;

use App\Infra\Adapters\Mappers\Zodiac;

interface ZodiacRepositoryInterface
{
    public function getSignByBirth(\DateTime $birth): Zodiac;
}