<?php

declare(strict_types=1);

namespace Payroll\Shared;

use DateTimeImmutable;

class FakeClock implements Clock
{
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable('2005-03-14');
    }
}
