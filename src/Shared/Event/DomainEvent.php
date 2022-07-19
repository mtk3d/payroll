<?php

declare(strict_types=1);

namespace Payroll\Shared\Event;

use Payroll\Shared\UUID\UUID;

interface DomainEvent
{
    public function eventId(): UUID;
}
