<?php

declare(strict_types=1);

namespace Payroll\Shared;

class InMemoryDomainEventBus implements DomainEventBus
{
    /** @var DomainEvent[] */
    private array $events;

    public function __construct()
    {
        $this->events = [];
    }

    public function dispatch(DomainEvent $event): void
    {
        $this->events[] = $event;
    }

    public function latestEvent(): ?DomainEvent
    {
        return end($this->events) ?: null;
    }
}
