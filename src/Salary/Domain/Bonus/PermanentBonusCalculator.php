<?php

declare(strict_types=1);

namespace Payroll\Salary\Domain\Bonus;

use Money\Money;
use Payroll\Shared\Clock;

class PermanentBonusCalculator implements BonusCalculator
{
    private Money $amount;

    public function __construct(private Clock $clock, int $value)
    {
        if (0 > $value) {
            throw new \InvalidArgumentException('PermanentBonus value cannot be lower than zero');
        }

        $this->amount = Money::USD($value);
    }

    public function calculate(BonusCriteria $criteria): Money
    {
        $seniorityYears = $criteria->employmentDate->diff($this->clock->now())->y;
        $bonus = $this->amount->multiply($seniorityYears);
        return $criteria->baseSalary->add($bonus);
    }
}
