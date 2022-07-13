<?php

declare(strict_types=1);

namespace Payroll\Salary\Domain\Bonus;

use Carbon\Carbon;
use Money\Money;

class PermanentBonus implements BonusCalculator
{
    private Money $amount;

    public function __construct(int $value)
    {
        if (0 > $value) {
            throw new \InvalidArgumentException('PermanentBonus value cannot be lower than zero');
        }

        $this->amount = Money::USD($value);
    }

    public function calculate(BonusCriteria $criteria): Money
    {
        $seniorityYears = $criteria->employmentDate->diffInYears(Carbon::now());
        $bonus = $this->amount->multiply($seniorityYears);
        return $criteria->baseSalary->add($bonus);
    }
}
