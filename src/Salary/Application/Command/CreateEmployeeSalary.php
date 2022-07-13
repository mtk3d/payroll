<?php

declare(strict_types=1);

namespace Payroll\Salary\Application\Command;

use DateTimeImmutable;
use Money\Money;
use Payroll\Shared\DepartmentId;
use Payroll\Shared\EmployeeId;

class CreateEmployeeSalary
{
    public function __construct(
        readonly EmployeeId $id,
        readonly DateTimeImmutable $employmentDate,
        readonly Money $baseSalary,
        readonly DepartmentId $departmentId
    ) {}
}
