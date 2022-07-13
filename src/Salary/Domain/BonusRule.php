<?php

declare(strict_types=1);

namespace Payroll\Salary\Domain;

use Money\Money;

interface BonusRule
{
    public function calculate(BonusCriteria $criteria): Money;
}
