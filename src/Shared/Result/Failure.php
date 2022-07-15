<?php

declare(strict_types=1);

namespace Payroll\Shared\Result;

use Payroll\Shared\DomainEvent;
use Payroll\Shared\Result;

final class Failure extends Result
{
    /**
     * @param DomainEvent[] $events
     */
    public function __construct(protected string $reason, protected array $events)
    {
    }
}
