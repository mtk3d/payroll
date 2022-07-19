<?php

declare(strict_types=1);

namespace Payroll\Salary\Domain;

use Money\Money;
use Payroll\Shared\Event\DomainEvent;
use Payroll\Shared\UUID\EmployeeId;
use Payroll\Shared\UUID\ReportId;
use Payroll\Shared\UUID\UUID;

class SalaryCalculated implements DomainEvent
{
    public function __construct(
        private UUID $eventId,
        readonly EmployeeId $employeeId,
        readonly ReportId $reportId,
        readonly Money $baseSalary,
        readonly Money $bonus
    ) {
    }

    public static function newOne(EmployeeId $id, ReportId $reportId, Money $baseSalary, Money $bonus): self
    {
        return new self(UUID::random(), $id, $reportId, $baseSalary, $bonus);
    }

    public function eventId(): UUID
    {
        return $this->eventId;
    }
}
