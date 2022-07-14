<?php

declare(strict_types=1);

namespace Payroll\Salary\Domain;

use InvalidArgumentException;
use Payroll\Salary\Domain\Bonus\BonusType;

class BonusRule
{
    public function __construct(
        readonly BonusType $bonusType,
        readonly int $value
    ) {
        if (0 > $this->value) {
            throw new InvalidArgumentException('Bonus value cannot be lower than 0');
        }
    }
}
