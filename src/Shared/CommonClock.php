<?php

declare(strict_types=1);

namespace Payroll\Shared;

use DateTimeImmutable;
use DateTimeZone;

class CommonClock implements Clock
{
    private DateTimeZone $timezone;

    public function __construct(string $timezone)
    {
        $this->timezone = new DateTimeZone($timezone);
    }

    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable('now', $this->timezone);
    }
}
