<?php

declare(strict_types=1);

namespace Payroll\Shared\Event;

use Symfony\Component\Messenger\MessageBusInterface;

class MessengerDomainEventBus implements DomainEventBus
{
    public function __construct(private MessageBusInterface $bus)
    {
    }

    public function dispatch(DomainEvent $event): void
    {
        $this->bus->dispatch($event);
    }
}
