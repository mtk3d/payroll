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

    /**
     * @return numeric-string
     */
    public function toString(): string
    {
        $value = strval($this->value / 10000);
        if (!is_numeric($value)) {
            return '0';
        }

        return $value;
    }
}
