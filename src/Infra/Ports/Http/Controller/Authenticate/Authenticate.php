<?php

namespace App\Infra\Ports\Http\Controller\Authenticate;

use App\App\UseCase\Authenticate\AuthenticateUseCase;
use App\App\UseCase\Authenticate\Input\AuthenticateInput;
use App\Domain\Exceptions\InvalidParamsException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/token')]
final class Authenticate extends AbstractController
{
    public function __construct(
        private readonly AuthenticateUseCase $authenticateUseCase
    )
    {
    }

    /**
     * @throws InvalidParamsException
     */
    #[Route('', methods: [ 'POST' ])]
    public function create(Request $request): JsonResponse
    {
        $input = AuthenticateInput::fromArray($request->request->all());
        $output = $this->authenticateUseCase->execute(input: $input);
        return new JsonResponse($output->getData(), $output->getCode());
    }
}
