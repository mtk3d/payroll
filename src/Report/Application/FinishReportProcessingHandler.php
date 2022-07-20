<?php

declare(strict_types=1);

namespace Payroll\Report\Application;

use Payroll\Report\Application\Command\FinishReportProcessing;
use Payroll\Report\Domain\Exception\ReportFinishingException;
use Payroll\Report\Domain\ReportRepository;
use Payroll\Shared\CQRS\CommandHandler;
use Payroll\Shared\Event\DomainEventBus;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
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
            throw new ReportFinishingException($result->reason());
        }

        $this->repository->save($report);
        array_map([$this->bus, 'dispatch'], $result->events());
    }
}
