<?php

declare(strict_types=1);

namespace App\ReadModel\Report\Query;

use Payroll\Shared\Query;
use Payroll\Shared\UUID\ReportId;

class ListReportLines implements Query
{
    public function __construct(readonly ReportId $reportId)
    {
    }
}
