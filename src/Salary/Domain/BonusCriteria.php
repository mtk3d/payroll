<?php

declare(strict_types=1);

namespace Payroll\Salary\Domain;

use Carbon\Carbon;
use Money\Money;

class BonusCriteria
{
    public function __construct(readonly Carbon $employmentDate, readonly Money $baseSalary) {}
}
