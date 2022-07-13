<?php

declare(strict_types=1);

namespace Payroll\Salary\Domain\Bonus;

enum BonusType
{
    case PERCENTAGE;
    case PERMANENT;
}
