<?php

declare(strict_types=1);

namespace Payroll\Salary\Domain;

use Carbon\Carbon;
use Money\Money;
use Payroll\Shared\EmployeeId;

class Employee
{
    readonly Money $fullSalary;

    public function __construct(
        private EmployeeId $employeeId,
        private Carbon $employmentDate,
        private Money $baseSalary,
        private Department $department
    ) {
        $bonusRule = $this->department->bonusRule();
        $bonusCriteria = new BonusCriteria($this->employmentDate, $this->baseSalary);
        $this->fullSalary = $bonusRule->calculate($bonusCriteria);
    }
}
