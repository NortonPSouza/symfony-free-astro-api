<?php

namespace App\App\Contracts\Repository;

use App\Domain\Entity\Zodiac;

interface ZodiacRepositoryInterface
{
    public function getSignByBirth(\DateTime $birth): Zodiac;
}