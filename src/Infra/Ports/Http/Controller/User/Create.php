<?php

namespace App\Infra\Ports\Http\Controller\User;

use App\App\UseCase\User\Create\CreateUserUseCase;
use App\App\UseCase\User\Create\Input\CreateUserInput;
use App\Domain\Exceptions\InvalidParamsException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/user',  defaults: ['_authenticated' => true])]
final class Create extends AbstractController
{
    public function __construct(
        private readonly CreateUserUseCase $createUserUseCase,
    )
    {
    }

    /**
     * @throws InvalidParamsException
     * @throws \DateMalformedStringException
     */
    #[Route('', methods: [ 'POST' ])]
    public function create(Request $request): Response
    {
        $input = CreateUserInput::fromArray($request->request->all());
        $output = $this->createUserUseCase->execute(input: $input);
        return new JsonResponse($output->getData(), $output->getCode());
    }
}
