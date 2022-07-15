<?php

declare(strict_types=1);

namespace Test\Unit\Report;

use DateTimeImmutable;
use Payroll\Report\Application\Command\FinishReportProcessing;
use Payroll\Report\Application\Command\GenerateSalaryReport;
use Payroll\Report\Application\FinishReportProcessingHandler;
use Payroll\Report\Application\GenerateSalaryReportHandler;
use Payroll\Report\Domain\Report;
use Payroll\Report\Domain\ReportCreated;
use Payroll\Report\Domain\ReportGenerated;
use Payroll\Report\Infrastructure\Repository\InMemoryReportRepository;
use Payroll\Shared\InMemoryDomainEventBus;
use Payroll\Shared\ReportId;
use PHPUnit\Framework\TestCase;

class ReportTest extends TestCase
{
    private InMemoryDomainEventBus $bus;
    private InMemoryReportRepository $repository;

    public function setUp(): void
    {
        $this->bus = new InMemoryDomainEventBus();
        $this->repository = new InMemoryReportRepository();
    }

    public function testCreateReport(): void
    {
        // Setup
        $handler = new GenerateSalaryReportHandler($this->bus, $this->repository);

        // Given
        $reportId = ReportId::newOne();
        $date = new DateTimeImmutable('2005-03-14');

        // When
        $command = new GenerateSalaryReport($reportId, $date);
        $handler->handle($command);

        // Then
        $event = $this->bus->latestEvent();
        $expected = new ReportCreated($event->eventId(), $reportId, $date, 'PROCESSING');
        self::assertEquals($expected, $event);
    }

    public function testFinishGenerating(): void
    {
        // Setup
        $handler = new FinishReportProcessingHandler($this->bus, $this->repository);

        // Given
        $reportId = ReportId::newOne();
        $date = new DateTimeImmutable('2005-03-14');
        $report = new Report($reportId, $date);
        $this->repository->save($report);

        // When
        $command = new FinishReportProcessing($reportId);
        $handler->handle($command);

        // Then
        $event = $this->bus->latestEvent();
        $expected = new ReportGenerated($event->eventId(), $reportId);
        self::assertEquals($expected, $event);
    }
}
