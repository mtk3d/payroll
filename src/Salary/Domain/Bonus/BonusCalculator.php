<?php

declare(strict_types=1);

namespace Payroll\Salary\Domain\Bonus;

use Money\Money;

interface BonusCalculator
{
    public function calculate(BonusCriteria $criteria): Money;
}
