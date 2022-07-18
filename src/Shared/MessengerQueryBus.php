<?php

declare(strict_types=1);

namespace Payroll\Shared;

use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class MessengerQueryBus implements QueryBus
{
    public function __construct(private MessageBusInterface $bus)
    {
    }

    public function query(Query $query)
    {
        $envelope = $this->bus->dispatch($query);
        $handledStamp = $envelope->last(HandledStamp::class);

        return $handledStamp?->getResult();
    }
}
