<?php

declare(strict_types=1);

namespace Payroll\Salary\Domain;

use Money\Money;
use Payroll\Salary\Domain\Bonus\BonusCriteria;

interface BonusCalculator
{
    public function calculate(BonusCriteria $criteria): Money;
}
