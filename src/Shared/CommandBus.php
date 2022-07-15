<?php

declare(strict_types=1);

namespace Payroll\Shared;

interface CommandBus
{
    public function dispatch(Command $command): void;
}
