<?php

declare(strict_types=1);

namespace Payroll\Salary\Domain;

use Payroll\Salary\Domain\Bonus\BonusType;

class BonusRule
{
    public function __construct(readonly BonusType $bonusType, readonly int $value) {}
}
