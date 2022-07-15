<?php

declare(strict_types=1);

namespace Payroll\Employment\Application\Command;

use Payroll\Shared\Command;
use Payroll\Shared\DepartmentId;
use Payroll\Shared\EmployeeId;

class CreateEmployee implements Command
{
    public function __construct(
        readonly EmployeeId $id,
        readonly string $firstname,
        readonly string $lastname,
        readonly DepartmentId $departmentId
    ) {
    }
}
