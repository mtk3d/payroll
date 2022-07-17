<?php

declare(strict_types=1);

namespace Payroll\Shared;

use Symfony\Component\Messenger\MessageBusInterface;

class MessengerDomainEventBus implements DomainEventBus
{
    private MessageBusInterface $eventBus;

    public function __construct(MessageBusInterface $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    public function dispatch(DomainEvent $event): void
    {
        $this->eventBus->dispatch($event);
    }
}
