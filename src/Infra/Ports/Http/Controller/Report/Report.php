<?php

namespace App\Infra\Ports\Http\Controller\Report;

use App\App\UseCase\Report\Create\CreateReportUseCase;
use App\App\UseCase\Report\Create\Input\CreateReportInput;
use App\Domain\Exceptions\InvalidParamsException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/report',  defaults: ['_authenticated' => true])]
final class Report extends AbstractController
{

    public function __construct(
        private readonly CreateReportUseCase $createReportUseCase,
    )
    {
    }

    /**
     * @throws InvalidParamsException
     */
    #[Route('', methods: [ 'POST' ])]
    public function create(Request $request): JsonResponse
    {
        $input = CreateReportInput::fromArray($request->request->all());
        $output = $this->createReportUseCase->execute(input: $input);
        return new JsonResponse($output->getData(), $output->getCode());
    }
}