<?php

declare(strict_types=1);

namespace Payroll\Salary\Application\Command;

use Payroll\Shared\Command;
use Payroll\Shared\ReportId;

class CalculateReportSalaries implements Command
{
    public function __construct(readonly ReportId $reportId)
    {
    }
}
