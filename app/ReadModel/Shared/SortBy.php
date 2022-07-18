<?php

declare(strict_types=1);

namespace App\ReadModel\Shared;

class SortBy
{
    public function __construct(readonly string $column, readonly bool $asc)
    {
    }

    public static function fromQueryString(string $query): SortBy
    {
        $params = explode(' ', $query);
        $column = $params[0];
        $direction = $params[1] ?? 'asc';

        return new self($column, 'asc' === $direction);
    }
}
