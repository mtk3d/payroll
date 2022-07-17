<?php

declare(strict_types=1);

namespace Payroll\Salary\Domain;

use Payroll\Shared\UUID\DepartmentId;

class Department
{
    public function __construct(
        readonly DepartmentId $id,
        private BonusRule $bonusRule
    ) {
    }

    public function bonusRule(): BonusRule
    {
        return $this->bonusRule;
    }
}
