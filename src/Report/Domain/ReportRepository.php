<?php

declare(strict_types=1);

namespace Payroll\Report\Domain;

interface ReportRepository
{
    public function save(Report $report): void;
}
