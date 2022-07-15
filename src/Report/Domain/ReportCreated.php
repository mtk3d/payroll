<?php

declare(strict_types=1);

namespace Payroll\Report\Domain;

use DateTimeImmutable;
use Payroll\Shared\DomainEvent;
use Payroll\Shared\ReportId;
use Payroll\Shared\UUID;

class ReportCreated implements DomainEvent
{
    public function __construct(
        private UUID $eventId,
        readonly ReportId $reportId,
        readonly DateTimeImmutable $date,
        readonly string $status
    ) {
    }

    public static function newOne(ReportId $reportId, DateTimeImmutable $date, string $status): self
    {
        return new self(UUID::random(), $reportId, $date, $status);
    }

    public function eventId(): UUID
    {
        return $this->eventId;
    }
}