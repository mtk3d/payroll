<?php

declare(strict_types=1);

namespace Payroll\Salary\Application\Command;

use DateTimeImmutable;
use Money\Money;
use Payroll\Shared\Command;
use Payroll\Shared\UUID\DepartmentId;
use Payroll\Shared\UUID\EmployeeId;

class CreateEmployeeSalary implements Command
{
    public function __construct(
        readonly EmployeeId $id,
        readonly DateTimeImmutable $employmentDate,
        readonly Money $baseSalary,
        readonly DepartmentId $departmentId
    ) {
    }
}
