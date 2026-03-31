<?php

namespace App\Infra\Adapters\Gateway;

use App\App\Contracts\Gateway\TokenManagerInterface;
use App\Domain\Entity\User;
use App\Domain\Exceptions\UnauthorizedException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

readonly class JwtManager implements TokenManagerInterface
{

    public function generate(User $user, int $expires): string
    {
        $payload = [
            'sub' => $user->getId(),
            'iat' => time(),
            'exp' => time() + $expires
        ];
        return JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');
    }

    /**
     * @throws UnauthorizedException
     */
    public function validate(string $token): string
    {
        try {
            $tokenDecoded = JWT::decode($token, new Key($_ENV['JWT_SECRET'], 'HS256'));
            return $tokenDecoded->sub;
        } catch (\Exception $exception) {
            throw new UnauthorizedException($exception->getMessage());
        }
    }
}