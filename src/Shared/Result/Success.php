<?php

declare(strict_types=1);

namespace Payroll\Shared\Result;

use Payroll\Shared\Event\DomainEvent;
use Payroll\Shared\Result;

final class Success extends Result
{
    /**
     * @param DomainEvent[] $events
     */
    public function __construct(protected array $events)
    {
    }
}
