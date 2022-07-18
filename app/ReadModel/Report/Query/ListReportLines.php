<?php

declare(strict_types=1);

namespace App\ReadModel\Report\Query;

use App\ReadModel\Shared\FilterBy;
use App\ReadModel\Shared\SortBy;
use Payroll\Shared\Query;
use Payroll\Shared\UUID\ReportId;

class ListReportLines implements Query
{
    /**
     * @param FilterBy[]|null $filters
     */
    public function __construct(
        readonly ReportId $reportId,
        readonly ?SortBy $sortBy = null,
        readonly ?array $filters = []
    ) {
    }
}
