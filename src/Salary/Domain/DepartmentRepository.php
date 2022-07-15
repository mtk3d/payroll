<?php

declare(strict_types=1);

namespace Payroll\Salary\Domain;

use Payroll\Salary\Domain\Exception\DepartmentAlreadyExistException;
use Payroll\Shared\DepartmentId;

interface DepartmentRepository
{
    /**
     * @throws DepartmentAlreadyExistException
     */
    public function save(Department $department): void;

    /**
     * @throws DepartmentNotFoundException
     */
    public function find(DepartmentId $departmentId): ?Department;
}
