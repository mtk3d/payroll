<?php

declare(strict_types=1);

namespace Payroll\Salary\Domain\Bonus;

use InvalidArgumentException;
use Money\Money;
use Payroll\Shared\Percent;

class PercentageBonusCalculator implements BonusCalculator
{
    private Percent $percent;

    public function __construct(int $value)
    {
        if (0 > $value) {
            throw new InvalidArgumentException('PercentageBonus cannot be lower than zero');
        }

        $this->percent = Percent::of($value);
    }

    public function calculate(BonusCriteria $criteria): Money
    {
        $baseSalary = $criteria->baseSalary;

        return $baseSalary->multiply($this->percent->toString());
    }
}
