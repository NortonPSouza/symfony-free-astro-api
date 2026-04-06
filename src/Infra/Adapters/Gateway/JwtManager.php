<?php

namespace App\Infra\Adapters\Gateway;

use App\App\Contracts\Gateway\TokenManagerInterface;
use App\Domain\Entity\User;
use App\Domain\Exceptions\UnauthorizedException;
use App\Domain\ValueObjects\Token;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

readonly class JwtManager implements TokenManagerInterface
{

    public function generate(User $user, int $expires): Token
    {
        $payload = [
            'sub' => $user->getId(),
            'iat' => time(),
            'exp' => time() + $expires
        ];
        $token = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');
        return  new Token($token, $expires);
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