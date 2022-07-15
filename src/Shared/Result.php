<?php

declare(strict_types=1);

namespace Payroll\Shared;

use Payroll\Shared\Result\Failure;
use Payroll\Shared\Result\Success;

abstract class Result
{
    protected string $reason;
    /** @var DomainEvent[] */
    protected array $events;

    /**
     * @param DomainEvent[]|null $events
     * @return Success
     */
    public static function success(?DomainEvent ...$events): Success
    {
        return new Success($events);
    }

    /**
     * @param string $reason
     * @param DomainEvent[]|null $events
     */
    public static function failure(string $reason, ?DomainEvent ...$events): Failure
    {
        return new Failure($reason, $events);
    }

    public function isFailure(): bool
    {
        return $this instanceof Failure;
    }

    public function isSuccessful(): bool
    {
        return $this instanceof Success;
    }

    public function reason(): string
    {
        if ($this->isFailure()) {
            return $this->reason;
        }

        return 'OK';
    }

    /**
     * @return DomainEvent[]
     */
    public function events(): array
    {
        return $this->events;
    }
}
