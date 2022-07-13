<?php

declare(strict_types=1);

namespace Payroll\Salary\Infrastructure\Repository;

use Payroll\Salary\Domain\Employee;
use Payroll\Salary\Domain\EmployeeRepository;

class InMemoryEmployeeRepository implements EmployeeRepository
{
    /**
     * @param Employee[] $employees
     */
    public function __construct(private array $employees)
    {}

    /**
     * @return Employee[]
     */
    public function all(): array
    {
        return $this->employees;
    }
}
