<?php

declare(strict_types=1);

namespace Payroll\Salary\Infrastructure\Repository;

use Payroll\Salary\Domain\Department;
use Payroll\Salary\Domain\DepartmentRepository;
use Payroll\Salary\Domain\Exception\DepartmentNotFoundException;
use Payroll\Shared\DepartmentId;

class InMemoryDepartmentRepository implements DepartmentRepository
{
    /** @var Department[] */
    private array $departments = [];

    public function save(Department $department): void
    {
        $this->departments[$department->id->toString()] = $department;
    }

    /**
     * @throws DepartmentNotFoundException
     */
    public function find(DepartmentId $departmentId): Department
    {
        if (isset($this->departments[$departmentId->toString()])) {
            return $this->departments[$departmentId->toString()];
        }

        throw new DepartmentNotFoundException(sprintf('Department %s not found', $departmentId->toString()));
    }
}
