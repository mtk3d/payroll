<?php

declare(strict_types=1);

namespace Payroll\Employment\Domain;

use Payroll\Shared\Event\DomainEvent;
use Payroll\Shared\UUID\DepartmentId;
use Payroll\Shared\UUID\UUID;

class DepartmentRegistered implements DomainEvent
{
    public function __construct(
        private UUID $eventId,
        readonly DepartmentId $departmentId,
        readonly string $name
    ) {
    }

    public static function newOne(DepartmentId $departmentId, string $name): self
    {
        return new self(UUID::random(), $departmentId, $name);
    }

    public function eventId(): UUID
    {
        return $this->eventId;
    }
}
