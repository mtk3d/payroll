<?php

declare(strict_types=1);

namespace Payroll\Salary\Domain\Bonus;

use InvalidArgumentException;

enum BonusType: string
{
    case PERCENTAGE = 'PERCENTAGE';
    case PERMANENT = 'PERMANENT';

    public static function fromString(string $type): self
    {
        return match ($type) {
            'PERCENTAGE' => self::PERCENTAGE,
            'PERMANENT' => self::PERMANENT,
            default => throw new InvalidArgumentException(sprintf('There is no BonusType %s', $type)),
        };
    }
}
