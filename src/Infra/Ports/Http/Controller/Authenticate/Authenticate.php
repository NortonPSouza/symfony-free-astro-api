<?php

namespace App\Infra\Ports\Http\Controller\Authenticate;

use App\App\Contracts\Database\MemoryInterface;
use App\App\UseCase\Authenticate\AuthenticateUseCase;
use App\App\UseCase\Authenticate\Input\AuthenticateInput;
use App\Domain\Exceptions\InvalidParamsException;
use App\Infra\Adapters\Database\ConnectionDoctrine;
use App\Infra\Adapters\Encoder\BcryptPasswordEncoder;
use App\Infra\Adapters\Gateway\JwtManager;
use App\Infra\Adapters\Memory\TokenRedis;
use App\Infra\Adapters\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/token')]
final class Authenticate extends AbstractController
{
    public function __construct(
        private readonly ConnectionDoctrine $connection,
        private readonly MemoryInterface $memoryConnection
    )
    {
    }

    #[Route('', methods: [ 'POST' ])]
    public function create(Request $request): JsonResponse
    {
        try {
            $input = AuthenticateInput::fromArray($request->request->all());
        } catch (InvalidParamsException $exception) {
            return new JsonResponse($exception->getData(), $exception->getStatusCode());
        }
        $userRepository = new UserRepository($this->connection);
        $passwordEncoder = new BcryptPasswordEncoder();
        $tokenManager = new JwtManager();
        $tokenMemory = new TokenRedis($this->memoryConnection);
        $authenticateUserUseCase = new AuthenticateUseCase(
            userRepository: $userRepository,
            passwordEncoder: $passwordEncoder,
            tokenManager: $tokenManager,
            tokenMemory: $tokenMemory
        );
        $output = $authenticateUserUseCase->execute(input: $input);
        return new JsonResponse($output->getData(), $output->getCode());
    }
}