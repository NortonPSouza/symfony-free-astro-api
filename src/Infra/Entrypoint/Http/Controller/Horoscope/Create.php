<?php

namespace App\Infra\Entrypoint\Http\Controller\Horoscope;

use App\App\UseCase\Horoscope\Create\CreateHoroscopeUseCase;
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
        private readonly CreateHoroscopeUseCase $createHoroscopeUseCase,
    )
    {
    }

    /**
     * @throws InvalidParamsException
     * @throws \DateMalformedStringException
     */
    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $input = CreateHoroscopeInput::fromArray($request->request->all());
        $userId = $request->attributes->get('user_id');
        $output = $this->createHoroscopeUseCase->execute(input: $input, userId: $userId);
        return new JsonResponse($output->getData(), $output->getCode());
    }
}
