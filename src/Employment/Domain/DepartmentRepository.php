<?php

declare(strict_types=1);

namespace Payroll\Employment\Domain;

use Payroll\Shared\UUID\DepartmentId;

interface DepartmentRepository
{
    public function save(Department $department): void;

    public function find(DepartmentId $departmentId): Department;
}
