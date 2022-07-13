<?php

declare(strict_types=1);

namespace Payroll\Salary\Domain;

use Money\Money;
use Payroll\Shared\DomainEvent;
use Payroll\Shared\EmployeeId;

class SalaryCalculated implements DomainEvent
{
    public function __construct(readonly EmployeeId $id, readonly Money $amount) {}
}
