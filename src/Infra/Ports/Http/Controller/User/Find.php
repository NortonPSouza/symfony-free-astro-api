<?php

namespace App\Infra\Ports\Http\Controller\User;

use App\App\UseCase\User\Find\FindUserUseCase;
use App\App\UseCase\User\Find\Input\FindUserInput;
use App\Infra\Adapters\Database\ConnectionDoctrine;
use App\Infra\Adapters\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/user/{userId}', defaults: ['_authenticated' => true])]
final class Find extends AbstractController
{
    public function __construct(
        private readonly ConnectionDoctrine $connection,
    )
    {
        set_exception_handler(null);
    }

    #[Route('', methods: [ 'GET' ])]
    public function find(string $userId): Response
    {
        $userRepository = new UserRepository(connection: $this->connection);
        $findUserUseCase = new FindUserUseCase(userRepository: $userRepository);
        $input = FindUserInput::fromArray($userId);
        $output = $findUserUseCase->execute(input: $input);
        return new JsonResponse($output->getData(), $output->getCode());
    }
}