<?php

declare(strict_types=1);

namespace Payroll\Salary\Domain\Bonus;

use DateTimeImmutable;
use Money\Money;

class BonusCriteria
{
    public function __construct(readonly DateTimeImmutable $employmentDate, readonly Money $baseSalary)
    {
    }
}
