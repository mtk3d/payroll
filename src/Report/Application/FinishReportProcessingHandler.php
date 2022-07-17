<?php

declare(strict_types=1);

namespace Payroll\Report\Application;

use Payroll\Report\Application\Command\FinishReportProcessing;
use Payroll\Report\Domain\ReportRepository;
use Payroll\Shared\CommandHandler;
use Payroll\Shared\DomainEventBus;

class FinishReportProcessingHandler implements CommandHandler
{
    public function __construct(private DomainEventBus $bus, private ReportRepository $repository)
    {
    }

    public function __invoke(FinishReportProcessing $command): void
    {
        $report = $this->repository->find($command->reportId);
        $result = $report->finishProcessing();

        if ($result->isFailure()) {
            // TODO decide what to do on failure
            return;
        }

        $this->repository->save($report);
        array_map([$this->bus, 'dispatch'], $result->events());
    }
}
