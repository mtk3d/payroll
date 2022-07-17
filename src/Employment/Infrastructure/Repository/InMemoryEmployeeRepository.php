<?php

declare(strict_types=1);

namespace Payroll\Employment\Infrastructure\Repository;

use Payroll\Employment\Domain\Employee;
use Payroll\Employment\Domain\EmployeeRepository;
use Payroll\Shared\UUID\EmployeeId;

class InMemoryEmployeeRepository implements EmployeeRepository
{
    /** @var Employee[] */
    private array $employees = [];

    public function save(Employee $employee): void
    {
        $this->employees[$employee->id->toString()] = $employee;
    }

    public function find(EmployeeId $employeeId): Employee
    {
        return $this->employees[$employeeId->toString()];
    }
}
