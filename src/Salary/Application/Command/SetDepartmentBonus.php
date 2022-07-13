<?php

declare(strict_types=1);

namespace Payroll\Salary\Application\Command;

use Payroll\Shared\DepartmentId;

class SetDepartmentBonus
{
    public function __construct(
        readonly DepartmentId $departmentId,
        readonly string $bonusType,
        readonly int $bonusValue
    ) {}
}