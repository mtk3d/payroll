<?php

declare(strict_types=1);

namespace Payroll\Shared;

use DateTimeImmutable;

class CommonClock implements Clock
{
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }
}
