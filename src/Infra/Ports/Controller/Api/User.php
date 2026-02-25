<?php

namespace App\Infra\Ports\Controller\Api;

use App\App\UseCase\User\Create\CreateUserUseCase;
use App\App\UseCase\User\Create\Input\CreateUserInput;
use App\Domain\Exceptions\InvalidParamsException;
use App\Infra\Adapters\Database\ConnectionDoctrine;
use App\Infra\Adapters\Repository\UserRepository;
use App\Infra\Adapters\Repository\ZodiacRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/user')]
final class User extends AbstractController
{
    public function __construct(
        private readonly ConnectionDoctrine $connection,
    )
    {
        set_exception_handler(null);
    }

    /**
     * @throws \DateMalformedStringException
     * @throws InvalidParamsException
     */
    #[Route('', methods: [ 'POST' ])]
    public function create(Request $request): Response
    {
        $userRepository = new UserRepository(connection: $this->connection);
        $zodiacRepository = new ZodiacRepository(connection: $this->connection);
        $createUserUseCase = new CreateUserUseCase(
            userRepository: $userRepository,
            zodiacRepository: $zodiacRepository
        );
        $input = CreateUserInput::fromArray($request->request->all());
        $output = $createUserUseCase->execute(input: $input);
        return new JsonResponse($output->jsonSerialize(), $output->getCode());
    }
}