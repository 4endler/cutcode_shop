<?php

namespace Domain\Product\Collections;

use Illuminate\Database\Eloquent\Collection;

class OptionValueCollection extends Collection
{
    public function keyValues()
    {
        return $this->mapToGroups(function ($item, $key) {
            return [
                $item->option->title => $item
            ];
        });
    }
}