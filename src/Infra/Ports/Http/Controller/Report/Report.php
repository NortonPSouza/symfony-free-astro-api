<?php

namespace App\Infra\Ports\Http\Controller\Report;

use App\App\UseCase\Report\Create\CreateReportUseCase;
use App\App\UseCase\Report\Create\Input\CreateReportInput;
use App\Domain\Exceptions\InvalidParamsException;
use App\Infra\Adapters\Database\ConnectionDoctrine;
use App\Infra\Adapters\Gateway\EventProcessReport;
use App\Infra\Adapters\Repository\ReportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/report',  defaults: ['_authenticated' => true])]
final class Report extends AbstractController
{

    public function __construct(
        private readonly ConnectionDoctrine $connection,
    )
    {
        set_exception_handler(null);
    }

    #[Route('', methods: [ 'POST' ])]
    public function create(Request $request): JsonResponse
    {
        try {
            $input = CreateReportInput::fromArray($request->request->all());
        } catch (InvalidParamsException $exception) {
            return new JsonResponse($exception->getData(), $exception->getStatusCode());
        }
        $reportRepository = new ReportRepository(connection: $this->connection);
        $eventProcessReport = new EventProcessReport();
        $creatReportUseCase = new CreateReportUseCase(
            reportRepository: $reportRepository,
            eventProcessReport: $eventProcessReport
        );
        $output = $creatReportUseCase->execute(input: $input);
        return new JsonResponse($output->getData(), $output->getCode());
    }
}