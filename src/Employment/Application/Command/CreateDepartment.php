<?php

declare(strict_types=1);

namespace Payroll\Employment\Application\Command;

use Payroll\Shared\DepartmentId;

class CreateDepartment
{
    public function __construct(
        readonly DepartmentId $id,
        readonly string $name
    ) {}
}
