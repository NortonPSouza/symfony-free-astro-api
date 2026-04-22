<?php

namespace App\Tests\Unit\UseCase\Report;

use App\App\Contracts\Gateway\PdfGeneratorInterface;
use App\App\Contracts\Repository\ReportLogRepositoryInterface;
use App\App\Contracts\Repository\ReportRepositoryInterface;
use App\App\Contracts\Repository\UserRepositoryInterface;
use App\App\UseCase\Report\Generate\GenerateReportUseCase;
use App\App\UseCase\Report\Generate\Input\GenerateReportInput;
use App\Domain\Entity\Report;
use App\Domain\Entity\User;
use App\Domain\Exceptions\PdfGenerationException;
use App\Domain\Types\ReportStatus;
use App\Domain\ValueObjects\Email;
use PHPUnit\Framework\TestCase;

class GenerateReportUseCaseTest extends TestCase
{
    private ReportRepositoryInterface $reportRepository;
    private ReportLogRepositoryInterface $reportLogRepository;
    private UserRepositoryInterface $userRepository;
    private PdfGeneratorInterface $pdfGenerator;
    private GenerateReportUseCase $useCase;

    protected function setUp(): void
    {
        $this->reportRepository = $this->createMock(ReportRepositoryInterface::class);
        $this->reportLogRepository = $this->createMock(ReportLogRepositoryInterface::class);
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->pdfGenerator = $this->createMock(PdfGeneratorInterface::class);

        $this->useCase = new GenerateReportUseCase(
            reportRepository: $this->reportRepository,
            reportLogRepository: $this->reportLogRepository,
            userRepository: $this->userRepository,
            pdfGenerator: $this->pdfGenerator
        );
    }

    private function buildReport(): Report
    {
        return new Report(
            'uuid-user-123',
            6,
            2025,
            ReportStatus::PROCESSING->getStatus(),
            'uuid-report-456',
            new \DateTime()
        );
    }

    private function buildUser(): User
    {
        return new User(
            'uuid-user-123',
            'John',
            'Doe',
            Email::create('john@example.com'),
            null,
            new \DateTime('1990-05-15'),
            null,
            null
        );
    }

    public function testGenerateReportSuccess(): void
    {
        $input = new GenerateReportInput('uuid-report-456');
        $report = $this->buildReport();
        $user = $this->buildUser();

        $this->reportRepository
            ->expects($this->exactly(2))
            ->method('updateStatus')
            ->willReturn($report);

        $this->reportRepository
            ->expects($this->once())
            ->method('findById')
            ->with('uuid-report-456')
            ->willReturn($report);

        $this->userRepository
            ->expects($this->once())
            ->method('find')
            ->with('uuid-user-123')
            ->willReturn($user);

        $this->pdfGenerator
            ->expects($this->once())
            ->method('generate')
            ->with($user, $report)
            ->willReturn('/path/to/report.pdf');

        $this->reportLogRepository
            ->expects($this->once())
            ->method('save')
            ->with($report);

        $output = $this->useCase->execute($input);

        $this->assertEquals(200, $output->getCode());
    }

    public function testGenerateReportPdfFailure(): void
    {
        $input = new GenerateReportInput('uuid-report-456');
        $report = $this->buildReport();
        $user = $this->buildUser();

        $this->reportRepository
            ->expects($this->exactly(2))
            ->method('updateStatus');

        $this->reportRepository
            ->expects($this->once())
            ->method('findById')
            ->willReturn($report);

        $this->userRepository
            ->expects($this->once())
            ->method('find')
            ->willReturn($user);

        $this->pdfGenerator
            ->expects($this->once())
            ->method('generate')
            ->willThrowException(new PdfGenerationException('PDF generation failed'));

        $output = $this->useCase->execute($input);

        $this->assertEquals(500, $output->getCode());
    }
}
