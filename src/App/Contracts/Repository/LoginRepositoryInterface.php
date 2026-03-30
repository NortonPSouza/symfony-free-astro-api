<?php

namespace App\App\Contracts\Repository;

use App\Domain\Entity\Login;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\Exceptions\RepositoryException;

interface LoginRepositoryInterface
{
    /**
     * @param string $email
     * @return Login
     * @throws RepositoryException|NotFoundException
     */
    public function findByEmail(string $email): Login;

}