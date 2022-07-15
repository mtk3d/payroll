<?php

declare(strict_types=1);

namespace Payroll\Employment\Domain;

use Payroll\Shared\DepartmentId;

class Department
{
    public function __construct(
        readonly DepartmentId $id,
        private string $name
    ) {
    }
}
