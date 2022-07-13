<?php

declare(strict_types=1);

namespace Payroll\Salary\Domain;

use Payroll\Shared\DepartmentId;

class Department
{
    public function __construct(private DepartmentId $id, readonly BonusRule $bonusRule) {}
}
