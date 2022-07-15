<?php

declare(strict_types=1);

namespace Payroll\Report\Domain;

use DateTimeImmutable;
use Payroll\Shared\ReportId;

class Report
{
    private ReportStatus $status;

    public function __construct(readonly ReportId $id, private DateTimeImmutable $date)
    {
        $this->status = ReportStatus::PROCESSING;
    }

    public function date(): DateTimeImmutable
    {
        return $this->date;
    }
}
