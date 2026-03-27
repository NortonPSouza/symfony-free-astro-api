<?php

namespace App\App\Contracts\Gateway;

use App\Domain\Entity\User;

interface TokenManagerInterface
{
    public function generate(User $user, int $expires): string;
    public function validate(string $token): string;
}