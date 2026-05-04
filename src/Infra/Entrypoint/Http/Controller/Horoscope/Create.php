<?php

namespace App\Infra\Entrypoint\Http\Controller\Horoscope;

use App\App\UseCase\Horoscope\Create\CreateUseCase;
use App\App\UseCase\Horoscope\Create\Input\CreateHoroscopeInput;
use App\Domain\Exceptions\InvalidParamsException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/horoscope', defaults: ['_authenticated' => true])]
final class Create extends AbstractController
{
    public function __construct(
        private readonly CreateUseCase $createUseCase,
    )
    {
    }

    /**
     * @throws \DateMalformedStringException
     */
    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $body = $request->request->all();
        $input = CreateHoroscopeInput::fromArray(
            $body['messages'],
            new \DateTime($body['start_date']),
            new \DateTime($body['end_date'])
        );
        $output = $this->createUseCase->execute(input: $input);
        return new JsonResponse($output->getData(), $output->getCode());
    }
}
