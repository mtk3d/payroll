<?php

declare(strict_types=1);

namespace Payroll\Shared\UUID;

use Ramsey\Uuid\Uuid as RamseyUuid;

class UUID
{
    private string $value;

    public function __construct(string $value)
    {
        if (!RamseyUuid::isValid($value)) {
            throw new \InvalidArgumentException('Invalid UUID format');
        }

        $this->value = $value;
    }

    public static function random(): self
    {
        return new self(RamseyUuid::getFactory()->uuid4()->toString());
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function isEqual(self $id): bool
    {
        return $this->value === $id->value;
    }
}
