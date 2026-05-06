<?php

namespace App\Infra\Entrypoint\Http\Controller\Horoscope;

use App\App\UseCase\Horoscope\Find\FindHoroscopeUseCase;
use App\App\UseCase\Horoscope\Find\Input\FindHoroscopeInput;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/horoscope/{zodiacId}', defaults: ['_authenticated' => true])]
final class Find extends AbstractController
{
    public function __construct(
        private readonly FindHoroscopeUseCase $findHoroscopeUseCase,
    )
    {
    }

    #[Route('', methods: ['GET'])]
    public function find(string $zodiacId): JsonResponse
    {
        $input = FindHoroscopeInput::fromArray($zodiacId);
        $output = $this->findHoroscopeUseCase->execute(input: $input);
        return new JsonResponse($output->getData(), $output->getCode());
    }
}
