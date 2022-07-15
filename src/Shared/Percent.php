<?php

declare(strict_types=1);

namespace Payroll\Shared;

class Percent
{
    public function __construct(readonly int $value)
    {
    }

    public static function of(int $value): self
    {
        return new Percent($value);
    }

    public function toString(): string
    {
        return strval($this->value / 10000);
    }
}
