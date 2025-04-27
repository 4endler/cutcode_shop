<?php

namespace Domain\Catalog\Sorters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Stringable;

final class Sorter
{
    public const SORT_KEY = 'sort';

    public function __construct(
        protected array $columns = []
    )
    {}

    public function run(Builder $query): Builder
    {
        $sortData = $this->sortData();

        return $query->when($sortData->contains($this->columns()), function (Builder $q) use ($sortData) {
            $q->orderBy(
                (string) $sortData->remove('-'),
                $sortData->contains('-') ? 'desc' : 'asc'
            );
        });
    }

    public function sortData(): Stringable
    {
        return request()->str($this->key());
    }

    public function key(): string
    {
        return self::SORT_KEY;
    }

    public function columns(): array
    {
        return $this->columns;
    }
    public function isActive(string $column, string $direction = 'asc'): bool
    {
        $column = trim($column, '-');

        if (strtolower($direction) === 'desc') {
            $column = "-$column";
        }
        return request()->str($this->key()) === $column;
    }

}