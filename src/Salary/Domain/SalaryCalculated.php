<?php

declare(strict_types=1);

namespace Payroll\Salary\Domain;

use Money\Money;
use Payroll\Shared\DomainEvent;
use Payroll\Shared\EmployeeId;
use Payroll\Shared\ReportId;
use Payroll\Shared\UUID;

class SalaryCalculated implements DomainEvent
{
    public function __construct(
        private UUID $eventId,
        readonly EmployeeId $id,
        readonly ReportId $reportId,
        readonly Money $amount
    ) {
    }

    public static function newOne(EmployeeId $id, ReportId $reportId, Money $amount): self
    {
        return new self(UUID::random(), $id, $reportId, $amount);
    }

    public function eventId(): UUID
    {
        return $this->eventId;
    }
}
