<?php

namespace App\Infra\Ports\Http\Controller\User;

use App\App\UseCase\User\Delete\DeleteUserUseCase;
use App\App\UseCase\User\Delete\Input\DeleteUserInput;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/user/{userId}',  defaults: ['_authenticated' => true])]
final class Delete extends AbstractController
{
    public function __construct(
        private readonly DeleteUserUseCase $deleteUserUseCase,
    )
    {
    }

    #[Route('', methods: [ 'DELETE' ])]
    public function delete(string $userId): Response
    {
        $input = DeleteUserInput::fromArray($userId);
        $output = $this->deleteUserUseCase->execute(input: $input);
        return new JsonResponse($output->getData(), $output->getCode());
    }
}
