<?php

declare(strict_types=1);

namespace Payroll\Salary\Infrastructure\Repository;

use Payroll\Salary\Domain\Department;
use Payroll\Salary\Domain\DepartmentRepository;
use Payroll\Shared\DepartmentId;

class InMemoryDepartmentRepository implements DepartmentRepository
{
    /** @var Department[] */
    private array $departments;

    public function save(Department $department): void
    {
        $this->departments[$department->id->toString()] = $department;
    }

    public function find(DepartmentId $departmentId): Department
    {
        return $this->departments[$departmentId->toString()];
    }
}
