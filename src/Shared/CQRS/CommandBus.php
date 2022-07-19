<?php

declare(strict_types=1);

namespace Payroll\Shared\CQRS;

interface CommandBus
{
    public function dispatch(Command $command): void;
}
