<?php

namespace App\Menu;

use Illuminate\Database\Eloquent\Collection;
use Support\Traits\Makeable;

final class Menu
{
    use Makeable;

    protected array $items = [];
    public function __construct(MenuItem ...$items) {
        $this->items = $items;
    }

    public function all():Collection
    {
        return Collection::make($this->items);
    }

    public function add(MenuItem $item): self
    {
        $this->items[] = $item;
        return $this;
    }
    public function addIf(bool|callable $condition, MenuItem $item): self
    {
        if (is_callable($condition) ? $condition() : $condition) {
            $this->items[] = $item;
        }
        return $this;
    }

    public function remove(MenuItem $item): self
    {
        $key = array_search($item, $this->items);
        if ($key !== false) {
            unset($this->items[$key]);
        }
        return $this;
    }

    public function removeByLink(string $link): self
    {
        $key = array_search($link, array_column($this->items, 'link'));
        if ($key !== false) {
            unset($this->items[$key]);
        }
        return $this;
    }
}