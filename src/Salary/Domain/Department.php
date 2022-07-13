<?php

declare(strict_types=1);

namespace Payroll\Salary\Domain;

class Department
{
    public function __construct(private $id, private BonusRule $bonusRule) {}

    public function bonusRule(): BonusRule
    {
        return $this->bonusRule;
    }
}
