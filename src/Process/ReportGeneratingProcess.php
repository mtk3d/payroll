<?php

declare(strict_types=1);

namespace Payroll\Process;

use Payroll\Report\Application\Command\FinishReportProcessing;
use Payroll\Report\Domain\ReportCreated;
use Payroll\Salary\Application\Command\CalculateReportSalaries;
use Payroll\Salary\Domain\ReportSalariesCalculated;
use Payroll\Shared\CommandBus;

class ReportGeneratingProcess
{
    public function __construct(private CommandBus $bus)
    {
    }

    public function handleSalaryReportCreated(ReportCreated $command): void
    {
        $this->bus->dispatch(
            new CalculateReportSalaries($command->reportId)
        );
    }

    public function handleReportSalariesCalculated(ReportSalariesCalculated $command): void
    {
        $this->bus->dispatch(
            new FinishReportProcessing($command->reportId)
        );
    }
}
