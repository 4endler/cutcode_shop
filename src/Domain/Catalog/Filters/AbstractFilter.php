<?php

namespace Domain\Catalog\Filters;

use Illuminate\Database\Eloquent\Builder;

abstract class AbstractFilter
{
    public function __invoke(Builder $query, $next) //Для того, чтобы можно было пропустить filter через Pipeline
    {
        $this->apply($query);
        return $next($query);
    }

    abstract public function title(): string;

    abstract public function key(): string;

    abstract public function apply(Builder $builder): Builder;

    abstract public function values(): array;

    abstract public function view(): string;

    public function requestValue(string $index = null, mixed $default = null):mixed
    {
        return request('filters.' . $this->key() . ($index ? ".$index" : ""), $default);
    }

    public function name(string $index = null): string
    {
        //name="filters[key]?[index]"
        return str($this->key())
            ->wrap('[', ']')
            ->prepend('filters')
            ->when($index, fn ($str) => $str->append("[$index]"))
            ->value();
    }

    public function id(string $index = null):string
    {
        return str($this->name($index))
            ->slug('_')
            ->value();
    }
}