<?php

declare(strict_types=1);

namespace Payroll\Employment\Domain;

use Payroll\Shared\EmployeeId;

class Employee
{
    public function __construct(
        readonly EmployeeId $id,
        private string $firstname,
        private string $lastname,
        private Department $department
    ) {}
}
