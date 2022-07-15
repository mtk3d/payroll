<?php

declare(strict_types=1);

namespace Payroll\Employment\Infrastructure\Repository;

use Payroll\Employment\Domain\Department;
use Payroll\Employment\Domain\DepartmentRepository;
use Payroll\Shared\DepartmentId;

class InMemoryDepartmentRepository implements DepartmentRepository
{
    /** @var Department[] */
    private array $departments = [];

    public function save(Department $department): void
    {
        $this->departments[$department->id->toString()] = $department;
    }

    public function find(DepartmentId $departmentId): Department
    {
        return $this->departments[$departmentId->toString()];
    }
}
