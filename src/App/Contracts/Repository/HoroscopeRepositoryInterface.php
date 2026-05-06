<?php

namespace App\App\Contracts\Repository;

use App\Domain\Entity\Horoscope;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\Exceptions\RepositoryException;

interface HoroscopeRepositoryInterface
{

    /**
     * @param Horoscope $horoscope
     * @throws RepositoryException
     */
    public function create(Horoscope $horoscope): void;

    /**
     * @param Horoscope $horoscope
     * @return Horoscope
     * @throws RepositoryException
     * @throws NotFoundException
     */
    public function find(Horoscope $horoscope): Horoscope;
}