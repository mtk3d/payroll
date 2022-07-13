<?php

declare(strict_types=1);

namespace Payroll\Shared;

interface DomainEventBus
{
    public function dispatch(DomainEvent $event): void;
}
