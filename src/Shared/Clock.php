<?php

declare(strict_types=1);

namespace Payroll\Shared;

use DateTimeImmutable;

interface Clock
{
    public function now(): DateTimeImmutable;
}
