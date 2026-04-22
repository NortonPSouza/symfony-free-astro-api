<?php

namespace App\Tests\Unit\UseCase\Report;

use App\App\Contracts\Repository\ReportRepositoryInterface;
use App\App\UseCase\Report\Create\CreateReportUseCase;
use App\App\UseCase\Report\Create\Input\CreateReportInput;
use App\Domain\Entity\Report;
use App\Domain\Exceptions\EventException;
use App\Domain\Exceptions\InvalidParamsException;
use App\Domain\Types\ReportStatus;
use App\Infra\Adapters\Gateway\EventProcessReport;
use PHPUnit\Framework\TestCase;

class CreateReportUseCaseTest extends TestCase
{
    private ReportRepositoryInterface $reportRepository;
    private EventProcessReport $eventProcessReport;
    private CreateReportUseCase $useCase;

    protected function setUp(): void
    {
        $this->reportRepository = $this->createMock(ReportRepositoryInterface::class);
        $this->eventProcessReport = $this->createMock(EventProcessReport::class);

        $this->useCase = new CreateReportUseCase(
            reportRepository: $this->reportRepository,
            eventProcessReport: $this->eventProcessReport
        );
    }

    public function testCreateReportSuccess(): void
    {
        $input = CreateReportInput::fromArray([
            'user_id' => 'uuid-user-123',
            'month' => 6,
            'year' => 2025,
        ]);

        $createdReport = new Report(
            'uuid-user-123',
            6,
            2025,
            ReportStatus::PENDING->getStatus(),
            'uuid-report-456',
            new \DateTime()
        );

        $this->reportRepository
            ->expects($this->once())
            ->method('create')
            ->willReturn($createdReport);

        $this->eventProcessReport
            ->expects($this->once())
            ->method('execute')
            ->with($createdReport);

        $output = $this->useCase->execute($input);

        $this->assertEquals(201, $output->getCode());
        $this->assertEquals(['id' => 'uuid-report-456'], $output->getData());
    }

    public function testCreateReportEventFailure(): void
    {
        $input = CreateReportInput::fromArray([
            'user_id' => 'uuid-user-123',
            'month' => 6,
            'year' => 2025,
        ]);

        $createdReport = new Report(
            'uuid-user-123',
            6,
            2025,
            ReportStatus::PENDING->getStatus(),
            'uuid-report-456',
            new \DateTime()
        );

        $this->reportRepository
            ->expects($this->once())
            ->method('create')
            ->willReturn($createdReport);

        $this->eventProcessReport
            ->expects($this->once())
            ->method('execute')
            ->willThrowException(new EventException('RabbitMQ connection failed'));

        $output = $this->useCase->execute($input);

        $this->assertEquals(502, $output->getCode());
    }

    public function testCreateReportInvalidInput(): void
    {
        $this->expectException(InvalidParamsException::class);

        CreateReportInput::fromArray([
            'user_id' => '',
            'month' => 13,
            'year' => 2025,
        ]);
    }
}
