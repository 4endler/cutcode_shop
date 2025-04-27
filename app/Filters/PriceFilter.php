<?php

namespace App\Filters;

use Domain\Catalog\Filters\AbstractFilter;
use Illuminate\Database\Eloquent\Builder;

final class PriceFilter extends AbstractFilter
{
    public function title(): string
    {
        return 'Цена';   
    }

    public function key(): string
    {
        return 'price';
    }

    public function apply(Builder $query): Builder
    {
        return $query->when($this->requestValue(), function (Builder $q) {
            $q->whereBetween('price', [
                $this->requestValue('from', 0),
                $this->requestValue('to', 10000),
            ]);
        });
    }

    public function values(): array
    {
        return [
            'from' => $this->requestValue('from', 0),
            'to' => $this->requestValue('to', 10000),
        ];
    }

    public function view(): string
    {
        return 'catalog.filters.price';
    }
}