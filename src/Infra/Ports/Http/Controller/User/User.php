<?php

namespace App\Infra\Ports\Http\Controller\User;

use App\App\UseCase\User\Create\CreateUserUseCase;
use App\App\UseCase\User\Create\Input\CreateUserInput;
use App\App\UseCase\User\Delete\DeleteUserUseCase;
use App\App\UseCase\User\Delete\Input\DeleteUserInput;
use App\App\UseCase\User\Find\FindUserUseCase;
use App\App\UseCase\User\Find\Input\FindUserInput;
use App\Domain\Exceptions\InvalidParamsException;
use App\Infra\Adapters\Database\ConnectionDoctrine;
use App\Infra\Adapters\Encoder\BcryptPasswordEncoder;
use App\Infra\Adapters\Repository\UserRepository;
use App\Infra\Adapters\Repository\ZodiacRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// TODO: remove God class, separate controllers for each method
#[Route('/api/v1/user')]
final class User extends AbstractController
{
    public function __construct(
        private readonly ConnectionDoctrine $connection,
    )
    {
        set_exception_handler(null);
    }

    #[Route('', methods: [ 'POST' ])]
    public function create(Request $request): Response
    {
        try {
            $input = CreateUserInput::fromArray($request->request->all());
        } catch (InvalidParamsException $exception) {
            return new JsonResponse($exception->getData(), $exception->getStatusCode());
        } catch (\DateMalformedStringException $exception) {
            return new JsonResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
        $userRepository = new UserRepository(connection: $this->connection);
        $zodiacRepository = new ZodiacRepository(connection: $this->connection);
        $createUserUseCase = new CreateUserUseCase(
            userRepository: $userRepository,
            zodiacRepository: $zodiacRepository
        );
        $passwordEncoder = new BcryptPasswordEncoder();
        $output = $createUserUseCase->execute(input: $input, passwordEncoder: $passwordEncoder);
        return new JsonResponse($output->getData(), $output->getCode());
    }

    #[Route('/{userId}', methods: [ 'GET' ])]
    public function find(int $userId): Response
    {
        $userRepository = new UserRepository(connection: $this->connection);
        $findUserUseCase = new FindUserUseCase(userRepository: $userRepository);
        $input = FindUserInput::fromArray($userId);
        $output = $findUserUseCase->execute(input: $input);
        return new JsonResponse($output->getData(), $output->getCode());
    }


    #[Route('/{userId}', methods: [ 'DELETE' ])]
    public function delete(int $userId): Response
    {
        $userRepository = new UserRepository(connection: $this->connection);
        $deleteUserUseCase = new DeleteUserUseCase(userRepository: $userRepository);
        $input = DeleteUserInput::fromArray($userId);
        $output = $deleteUserUseCase->execute(input: $input);
        return new JsonResponse($output->getData(), $output->getCode());
    }
}