<?php

declare(strict_types=1);

namespace Payroll\Shared;

interface DomainEvent
{
    public function eventId(): UUID;
}
