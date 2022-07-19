<?php

declare(strict_types=1);

namespace Payroll\Salary\Domain;

use Payroll\Shared\Event\DomainEvent;
use Payroll\Shared\UUID\DepartmentId;
use Payroll\Shared\UUID\UUID;

class DepartmentBonusChanged implements DomainEvent
{
    public function __construct(
        private UUID $eventId,
        readonly DepartmentId $departmentId,
        readonly string $bonusType,
        readonly int $bonusFactor
    ) {
    }

    public static function newOne(DepartmentId $departmentId, string $bonusType, int $bonusFactor): self
    {
        return new self(UUID::random(), $departmentId, $bonusType, $bonusFactor);
    }

    public function eventId(): UUID
    {
        return $this->eventId;
    }
}
