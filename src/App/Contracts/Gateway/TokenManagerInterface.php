<?php

namespace App\App\Contracts\Gateway;

use App\Domain\Entity\User;
use App\Domain\ValueObjects\Token;

interface TokenManagerInterface
{
    public function generate(User $user, int $expires): Token;
    public function validate(string $token): string;
}