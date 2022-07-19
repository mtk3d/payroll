<?php

declare(strict_types=1);

namespace Payroll\Shared\Event;

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

    public function firstEvent(): ?DomainEvent
    {
        reset($this->events);

        return current($this->events) ?: null;
    }

    /**
     * @return DomainEvent[]
     */
    public function events(): array
    {
        return $this->events;
    }
}
