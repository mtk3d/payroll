<?php

declare(strict_types=1);

namespace Payroll\Salary\Domain;

use Payroll\Shared\DepartmentId;
use Payroll\Shared\DomainEvent;
use Payroll\Shared\UUID;

class DepartmentBonusSet implements DomainEvent
{
    public function __construct(
        private UUID $eventId,
        readonly DepartmentId $departmentId,
        readonly string $bonusType,
        readonly int $bonusValue
    ) {}

    public static function newOne(DepartmentId $departmentId, string $bonusType, int $bonusValue): self
    {
        return new self(UUID::random(), $departmentId, $bonusType, $bonusValue);
    }

    public function eventId(): UUID
    {
        return $this->eventId;
    }
}
