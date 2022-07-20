<?php

declare(strict_types=1);

namespace Payroll\Salary\Domain;

use Payroll\Salary\Domain\Exception\DepartmentNotFoundException;
use Payroll\Shared\UUID\DepartmentId;

interface DepartmentRepository
{
    public function save(Department $department): void;

    /**
     * @throws DepartmentNotFoundException
     */
    public function find(DepartmentId $departmentId): Department;
}
