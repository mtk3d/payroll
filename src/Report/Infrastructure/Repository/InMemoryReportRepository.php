<?php

declare(strict_types=1);

namespace Payroll\Report\Infrastructure\Repository;

use Payroll\Report\Domain\Report;
use Payroll\Report\Domain\ReportRepository;

class InMemoryReportRepository implements ReportRepository
{
    /** @var Report[] */
    private array $reports;

    public function save(Report $report): void
    {
        $this->reports[$report->id->toString()] = $report;
    }
}
