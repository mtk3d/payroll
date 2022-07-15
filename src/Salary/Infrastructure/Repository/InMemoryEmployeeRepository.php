<?php

declare(strict_types=1);

namespace Payroll\Salary\Infrastructure\Repository;

use Payroll\Salary\Domain\Employee;
use Payroll\Salary\Domain\EmployeeRepository;

class InMemoryEmployeeRepository implements EmployeeRepository
{
    /** @var Employee[] */
    private array $employees = [];

    /**
     * @return Employee[]
     */
    public function all(): array
    {
        return $this->employees;
    }

    public function save(Employee $employee): void
    {
        $this->employees[$employee->employeeId->toString()] = $employee;
    }
}
