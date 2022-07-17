<?php

declare(strict_types=1);

namespace Payroll\Report\Application\Command;

use Payroll\Shared\Command;
use Payroll\Shared\UUID\ReportId;

class GenerateSalaryReport implements Command
{
    public function __construct(readonly ReportId $reportId)
    {
    }
}
