<?php

declare(strict_types=1);

namespace Payroll\Salary\Domain;

interface EmployeeRepository
{
    /**
     * @return Employee[]
     */
    public function all(): array;
}
