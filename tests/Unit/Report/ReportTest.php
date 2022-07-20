<?php

declare(strict_types=1);

namespace Tests\Unit\Report;

use Payroll\Report\Application\Command\FinishReportProcessing;
use Payroll\Report\Application\Command\GenerateSalaryReport;
use Payroll\Report\Application\FinishReportProcessingHandler;
use Payroll\Report\Application\GenerateSalaryReportHandler;
use Payroll\Report\Domain\Exception\ReportFinishingException;
use Payroll\Report\Domain\Exception\ReportNotFoundException;
use Payroll\Report\Domain\Report;
use Payroll\Report\Domain\ReportCreated;
use Payroll\Report\Domain\ReportProcessingFinished;
use Payroll\Report\Infrastructure\Repository\InMemoryReportRepository;
use Payroll\Shared\Clock;
use Payroll\Shared\Event\InMemoryDomainEventBus;
use Payroll\Shared\FakeClock;
use Payroll\Shared\UUID\ReportId;
use PHPUnit\Framework\TestCase;

class ReportTest extends TestCase
{
    private InMemoryDomainEventBus $bus;
    private InMemoryReportRepository $repository;
    private Clock $clock;

    public function setUp(): void
    {
        $this->bus = new InMemoryDomainEventBus();
        $this->repository = new InMemoryReportRepository();
        $this->clock = new FakeClock();
    }

    public function testCreateReport(): void
    {
        // Setup
        $handler = new GenerateSalaryReportHandler($this->bus, $this->repository, $this->clock);

        // Given
        $reportId = ReportId::newOne();
        $date = $this->clock->now();

        // When
        $command = new GenerateSalaryReport($reportId);
        $handler->__invoke($command);

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
        $date = $this->clock->now();
        $report = new Report($reportId, $date);
        $this->repository->save($report);

        // When
        $command = new FinishReportProcessing($reportId);
        $handler->__invoke($command);

        // Then
        $event = $this->bus->latestEvent();
        $expected = new ReportProcessingFinished($event->eventId(), $reportId);
        self::assertEquals($expected, $event);
    }

    public function testFailFinishGenerating(): void
    {
        // Expect
        self::expectException(ReportFinishingException::class);

        // Setup
        $handler = new FinishReportProcessingHandler($this->bus, $this->repository);

        // Given
        $reportId = ReportId::newOne();
        $date = $this->clock->now();
        $report = new Report($reportId, $date);
        $this->repository->save($report);

        // When
        $command = new FinishReportProcessing($reportId);
        $handler->__invoke($command);

        // And
        $handler->__invoke($command);
    }

    public function testReportNotFound(): void
    {
        // Expect
        self::expectException(ReportNotFoundException::class);

        // Setup
        $handler = new FinishReportProcessingHandler($this->bus, $this->repository);

        // Given nothing

        // When
        $command = new FinishReportProcessing(ReportId::newOne());
        $handler->__invoke($command);
    }
}
