<?php

declare(strict_types=1);

namespace Payroll\Shared;

class EmployeeId extends AbstractId
{
    public static function of(string $id): self
    {
        return new self(new UUID($id));
    }

    public static function newOne(): self
    {
        return new self(UUID::random());
    }

    public function isEqual(self $id): bool
    {
        return $this->id->isEqual($id->id());
    }
}
