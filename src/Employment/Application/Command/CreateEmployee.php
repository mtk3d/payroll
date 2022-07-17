<?php

declare(strict_types=1);

namespace Payroll\Employment\Application\Command;

use Payroll\Shared\Command;
use Payroll\Shared\UUID\DepartmentId;
use Payroll\Shared\UUID\EmployeeId;

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
