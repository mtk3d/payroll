<?php

declare(strict_types=1);

namespace Payroll\Salary\Domain;

use DateTimeImmutable;
use Money\Money;
use Payroll\Shared\DomainEvent;
use Payroll\Shared\UUID\DepartmentId;
use Payroll\Shared\UUID\EmployeeId;
use Payroll\Shared\UUID\UUID;

class EmployeeSalaryChanged implements DomainEvent
{
    public function __construct(
        private UUID $eventId,
        readonly EmployeeId $id,
        readonly DateTimeImmutable $employmentDate,
        readonly Money $baseSalary,
        readonly DepartmentId $departmentId
    ) {
    }

    public static function newOne(
        EmployeeId $id,
        DateTimeImmutable $employmentDate,
        Money $baseSalary,
        DepartmentId $departmentId
    ): self {
        return new self(UUID::random(), $id, $employmentDate, $baseSalary, $departmentId);
    }

    public function eventId(): UUID
    {
        return $this->eventId;
    }
}
