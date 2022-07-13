<?php

declare(strict_types=1);

namespace Payroll\Salary\Domain\Bonus;

use Money\Money;

class PercentageBonusCalculator implements BonusCalculator
{
    private float $multiplier;

    public function __construct(int $value)
    {
        $this->multiplier = $value / 10000;

        if (0 > $this->multiplier) {
            throw new \InvalidArgumentException('PercentageBonus cannot be lower than zero');
        }
    }

    public function calculate(BonusCriteria $criteria): Money
    {
        $baseSalary = $criteria->baseSalary;
        $bonus = $baseSalary->multiply(strval($this->multiplier));
        return $baseSalary->add($bonus);
    }
}
