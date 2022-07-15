<?php

declare(strict_types=1);

namespace Payroll\Report\Application\Command;

use Payroll\Shared\ReportId;

class GenerateSalaryReport
{
    public function __construct(readonly ReportId $reportId)
    {
    }
}
