<?php

declare(strict_types=1);

namespace Payroll\Report\Infrastructure\Repository;

use Payroll\Report\Domain\Exception\ReportNotFoundException;
use Payroll\Report\Domain\Report;
use Payroll\Report\Domain\ReportRepository;
use Payroll\Shared\UUID\ReportId;

class InMemoryReportRepository implements ReportRepository
{
    /** @var Report[] */
    private array $reports = [];

    public function find(ReportId $reportId): Report
    {
        if (isset($this->reports[$reportId->toString()])) {
            return $this->reports[$reportId->toString()];
        }

        throw new ReportNotFoundException(sprintf('Report %s does not exist', $reportId->toString()));
    }

    public function save(Report $report): void
    {
        $this->reports[$report->id->toString()] = $report;
    }
}
