<?php

declare(strict_types=1);

namespace Payroll\Salary\Domain;

interface DepartmentRepository
{
    public function save(Department $department): void;
}
