<?php

declare(strict_types=1);

namespace Payroll\Employment\Application\Command;

use Payroll\Shared\Command;
use Payroll\Shared\UUID\DepartmentId;

class CreateDepartment implements Command
{
    public function __construct(
        readonly DepartmentId $id,
        readonly string $name
    ) {
    }
}
