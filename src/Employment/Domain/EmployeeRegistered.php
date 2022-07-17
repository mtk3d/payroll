<?php

declare(strict_types=1);

namespace Payroll\Employment\Domain;

use Payroll\Shared\DomainEvent;
use Payroll\Shared\UUID\DepartmentId;
use Payroll\Shared\UUID\EmployeeId;
use Payroll\Shared\UUID\UUID;

class EmployeeRegistered implements DomainEvent
{
    public function __construct(
        private UUID $eventId,
        readonly EmployeeId $employeeId,
        readonly string $firstname,
        readonly string $lastname,
        readonly DepartmentId $departmentId
    ) {
    }

    public static function newOne(
        EmployeeId $employeeId,
        string $firstname,
        string $lastname,
        DepartmentId $departmentId
    ): self {
        return new self(UUID::random(), $employeeId, $firstname, $lastname, $departmentId);
    }

    public function eventId(): UUID
    {
        return $this->eventId;
    }
}
