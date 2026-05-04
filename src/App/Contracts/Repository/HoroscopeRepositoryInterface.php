<?php

namespace App\App\Contracts\Repository;

use App\Domain\Entity\Horoscope;
use App\Domain\Exceptions\RepositoryException;

interface HoroscopeRepositoryInterface
{

    /**
     * @param Horoscope $horoscope
     * @throws RepositoryException
     */
    public function create(Horoscope $horoscope): void;
}