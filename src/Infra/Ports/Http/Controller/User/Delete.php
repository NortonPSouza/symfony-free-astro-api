<?php

namespace App\Infra\Ports\Http\Controller\User;

use App\App\UseCase\User\Delete\DeleteUserUseCase;
use App\App\UseCase\User\Delete\Input\DeleteUserInput;
use App\Infra\Adapters\Database\ConnectionDoctrine;
use App\Infra\Adapters\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/user/{userId}',  defaults: ['_authenticated' => true])]
final class Delete extends AbstractController
{
    public function __construct(
        private readonly ConnectionDoctrine $connection,
    )
    {
        set_exception_handler(null);
    }

    #[Route('', methods: [ 'DELETE' ])]
    public function delete(string $userId): Response
    {
        $userRepository = new UserRepository(connection: $this->connection);
        $deleteUserUseCase = new DeleteUserUseCase(userRepository: $userRepository);
        $input = DeleteUserInput::fromArray($userId);
        $output = $deleteUserUseCase->execute(input: $input);
        return new JsonResponse($output->getData(), $output->getCode());
    }
}