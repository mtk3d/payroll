<?php

declare(strict_types=1);

namespace Payroll\Salary\Domain;

use DateTimeImmutable;
use Money\Money;
use Payroll\Salary\Domain\Bonus\BonusCriteria;
use Payroll\Shared\UUID\EmployeeId;

class Employee
{
    public function __construct(
        readonly EmployeeId $id,
        private DateTimeImmutable $employmentDate,
        private Money $baseSalary,
        private Department $department
    ) {
    }

    public function bonusRule(): BonusRule
    {
        return $this->department->bonusRule();
    }

    public function bonusCriteria(): BonusCriteria
    {
        return new BonusCriteria($this->employmentDate, $this->baseSalary);
    }
}
