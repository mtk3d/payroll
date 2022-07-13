<?php

declare(strict_types=1);

namespace Payroll\Salary\Domain;

use Money\Money;
use Payroll\Shared\EmployeeId;

class Employee
{
    private Money $fullSalary;

    public function __construct(
        private EmployeeId $employeeId,
        private Money $baseSalary,
        private Department $department
    ) {
        $bonusRule = $this->department->bonusRule();
        $this->fullSalary = $bonusRule->calculate($this->baseSalary);
    }

    public function fullSalary(): Money
    {
        return $this->fullSalary;
    }
}
