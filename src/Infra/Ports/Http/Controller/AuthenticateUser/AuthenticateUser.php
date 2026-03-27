<?php

namespace App\Infra\Ports\Http\Controller\AuthenticateUser;

use App\App\UseCase\AuthenticateUser\AuthenticateUserUseCase;
use App\App\UseCase\AuthenticateUser\Input\AuthenticateUserInput;
use App\Domain\Exceptions\InvalidParamsException;
use App\Infra\Adapters\Database\ConnectionDoctrine;
use App\Infra\Adapters\Encoder\BcryptPasswordEncoder;
use App\Infra\Adapters\Gateway\JwtManager;
use App\Infra\Adapters\Repository\LoginRepository;
use App\Infra\Adapters\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/authenticate/user')]
class AuthenticateUser extends AbstractController
{
    public function __construct(
        private readonly ConnectionDoctrine $connection,
    )
    {
        set_exception_handler(null);
    }

    #[Route('', methods: [ 'POST' ])]
    public function create(Request $request): JsonResponse
    {
        //TODO: remove try cath put on service the validate class
        try {
            $input = AuthenticateUserInput::fromArray($request->request->all());
        } catch (InvalidParamsException $exception) {
            return new JsonResponse($exception->getData(), $exception->getStatusCode());
        }
        $loginRepository = new LoginRepository($this->connection);
        $userRepository = new UserRepository($this->connection);
        $passwordEncoder = new BcryptPasswordEncoder();
        $tokenManager = new JwtManager();
        $authenticateUserUseCase = new AuthenticateUserUseCase(
            loginRepository: $loginRepository,
            userRepository: $userRepository,
            passwordEncoder: $passwordEncoder,
            tokenManager: $tokenManager
        );
        $output = $authenticateUserUseCase->execute(input: $input);
        return new JsonResponse($output->getData(), $output->getCode());
    }
}