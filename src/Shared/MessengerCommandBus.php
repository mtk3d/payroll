<?php

declare(strict_types=1);

namespace Payroll\Shared;

use Symfony\Component\Messenger\MessageBusInterface;

class MessengerCommandBus implements CommandBus
{
    public function __construct(private MessageBusInterface $bus)
    {
    }

    public function dispatch(Command $command): void
    {
        $this->bus->dispatch($command);
    }
}
