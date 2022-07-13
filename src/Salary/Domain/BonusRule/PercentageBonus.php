<?php

declare(strict_types=1);

namespace Payroll\Salary\Domain\BonusRule;

use Money\Money;
use Payroll\Salary\Domain\BonusCriteria;
use Payroll\Salary\Domain\BonusRule;

class PercentageBonus implements BonusRule
{
    private float $percent;

    public function __construct(float $percent)
    {
        if (0 > $percent) {
            throw new \InvalidArgumentException('PercentageBonus cannot be lower than zero');
        }

        $this->percent = $percent;
    }

    public function calculate(BonusCriteria $criteria): Money
    {
        $baseSalary = $criteria->baseSalary;
        $bonus = $baseSalary->multiply($this->multiplier());
        return $baseSalary->add($bonus);
    }

    private function multiplier(): string
    {
        return strval($this->percent / 100);
    }
}
