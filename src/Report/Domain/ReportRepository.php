<?php

declare(strict_types=1);

namespace Payroll\Report\Domain;

use Payroll\Shared\UUID\ReportId;

interface ReportRepository
{
    public function find(ReportId $reportId): Report;

    public function save(Report $report): void;
}
