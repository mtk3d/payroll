<?php

declare(strict_types=1);

namespace Payroll\Report\Domain;

use Payroll\Shared\Event\DomainEvent;
use Payroll\Shared\UUID\ReportId;
use Payroll\Shared\UUID\UUID;

class ReportProcessingFinished implements DomainEvent
{
    public function __construct(
        private UUID $eventId,
        readonly ReportId $reportId
    ) {
    }

    public static function newOne(ReportId $reportId): self
    {
        return new self(UUID::random(), $reportId);
    }

    public function eventId(): UUID
    {
        return $this->eventId;
    }
}
