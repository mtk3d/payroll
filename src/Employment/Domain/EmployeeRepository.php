<?php

declare(strict_types=1);

namespace Payroll\Employment\Domain;

interface EmployeeRepository
{
    public function save(Employee $employee): void;
}
