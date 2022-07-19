<?php

declare(strict_types=1);

namespace Payroll\Shared\Event;

interface DomainEventBus
{
    public function dispatch(DomainEvent $event): void;
}
