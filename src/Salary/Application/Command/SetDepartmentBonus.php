<?php

declare(strict_types=1);

namespace Payroll\Salary\Application\Command;

use Payroll\Shared\CQRS\Command;
use Payroll\Shared\UUID\DepartmentId;

class SetDepartmentBonus implements Command
{
    public function __construct(
        readonly DepartmentId $departmentId,
        readonly string $bonusType,
        readonly int $bonusFactor
    ) {
    }
}
