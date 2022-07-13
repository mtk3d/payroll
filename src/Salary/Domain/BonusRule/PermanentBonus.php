<?php

declare(strict_types=1);

namespace Payroll\Salary\Domain\BonusRule;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Money\Money;
use Payroll\Salary\Domain\BonusCriteria;
use Payroll\Salary\Domain\BonusRule;

class PermanentBonus implements BonusRule
{
    private Money $amount;

    public function __construct(Money $amount)
    {
        if ($amount->isNegative()) {
            throw new \InvalidArgumentException('PermanentBonus value cannot be lower than zero');
        }

        $this->amount = $amount;
    }

    public function calculate(BonusCriteria $criteria): Money
    {
        $seniorityYears = $criteria->employmentDate->diffInYears(Carbon::now());
        $bonus = $this->amount->multiply($seniorityYears);
        return $criteria->baseSalary->add($bonus);
    }
}
