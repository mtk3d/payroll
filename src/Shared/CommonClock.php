<?php

declare(strict_types=1);

namespace Payroll\Shared;

use DateTimeImmutable;
use DateTimeZone;

class CommonClock implements Clock
{
    public function __construct(private string $timezone)
    {
    }

    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable('now', new DateTimeZone($this->timezone));
    }
}
