<?php

declare(strict_types=1);

namespace Payroll\Salary\Domain;

use Payroll\Shared\DepartmentId;

class Department
{
    public function __construct(
        readonly DepartmentId $id,
        private BonusRule $bonusRule
    ) {}

    public function setBonusRule(BonusRule $bonusRule): void
    {
        $this->bonusRule = $bonusRule;
    }

    public function bonusRule(): BonusRule
    {
        return $this->bonusRule;
    }
}
