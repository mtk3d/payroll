<?php

declare(strict_types=1);

namespace Payroll\Salary\Infrastructure\Repository;

use Payroll\Salary\Domain\Department;
use Payroll\Salary\Domain\DepartmentRepository;

class InMemoryDepartmentRepository implements DepartmentRepository
{
    /** @var Department[] */
    private array $departments;

    public function save(Department $department): void
    {
        $this->departments[] = $department;
    }
}
