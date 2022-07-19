<?php

declare(strict_types=1);

namespace App\ReadModel\Shared;

class FilterBy
{
    public function __construct(readonly string $column, readonly string $value)
    {
    }

    /**
     * @return FilterBy[]
     */
    public static function ofList(array $filtersArr): array
    {
        $filters = [];
        foreach ($filtersArr as $column => $value) {
            $filters[] = new self($column, $value);
        }

        return $filters;
    }

    public function parameter(): string
    {
        return ":$this->column";
    }

    public function filterValue(): string
    {
        return "%$this->value%";
    }
}
