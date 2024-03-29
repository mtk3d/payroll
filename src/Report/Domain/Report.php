<?php

declare(strict_types=1);

namespace Payroll\Report\Domain;

use DateTimeImmutable;
use Payroll\Shared\Result;
use Payroll\Shared\UUID\ReportId;

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

    public function finishProcessing(): Result
    {
        if (ReportStatus::GENERATED === $this->status) {
            return Result::failure(sprintf('Report %s is already generated', $this->id->toString()));
        }

        $this->status = ReportStatus::GENERATED;

        return Result::success(ReportProcessingFinished::newOne($this->id));
    }
}
