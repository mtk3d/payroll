<?php

declare(strict_types=1);

namespace Payroll\Salary\Domain;

use Payroll\Shared\DepartmentId;

interface DepartmentRepository
{
    public function save(Department $department): void;

    public function find(DepartmentId $departmentId): Department;
}
