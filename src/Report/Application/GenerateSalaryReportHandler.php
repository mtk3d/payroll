<?php

declare(strict_types=1);

namespace Payroll\Report\Application;

use Payroll\Report\Application\Command\GenerateSalaryReport;
use Payroll\Report\Domain\Report;
use Payroll\Report\Domain\ReportCreated;
use Payroll\Report\Domain\ReportRepository;
use Payroll\Report\Domain\ReportStatus;
use Payroll\Shared\Clock;
use Payroll\Shared\DomainEventBus;

class GenerateSalaryReportHandler
{
    public function __construct(
        private DomainEventBus $bus,
        private ReportRepository $repository,
        private Clock $clock
    ) {
    }

    public function handle(GenerateSalaryReport $command): void
    {
        $dateTimeNow = $this->clock->now();
        $report = new Report($command->reportId, $dateTimeNow);

        $this->repository->save($report);

        $this->bus->dispatch(ReportCreated::newOne(
            $report->id,
            $report->date(),
            ReportStatus::PROCESSING->name
        ));
    }
}
