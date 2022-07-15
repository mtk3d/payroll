<?php

declare(strict_types=1);

namespace Payroll\Report\Application;

use Payroll\Report\Application\Command\GenerateSalaryReport;
use Payroll\Report\Domain\Report;
use Payroll\Report\Domain\ReportCreated;
use Payroll\Report\Domain\ReportRepository;
use Payroll\Report\Domain\ReportStatus;
use Payroll\Shared\DomainEventBus;

class GenerateSalaryReportHandler
{
    public function __construct(
        private DomainEventBus $bus,
        private ReportRepository $repository
    ) {}

    public function handle(GenerateSalaryReport $command): void
    {
        $report = new Report($command->reportId, $command->date);

        $this->repository->save($report);

        $this->bus->dispatch(ReportCreated::newOne(
            $report->id,
            $command->date,
            ReportStatus::PROCESSING->name
        ));
    }
}
