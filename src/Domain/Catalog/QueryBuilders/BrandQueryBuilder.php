<?php

namespace Domain\Catalog\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;

final class BrandQueryBuilder extends Builder
{
    public function homePage(): BrandQueryBuilder
    {
       return $this->where('on_home_page', true)->orderBy('rank')->limit(6);
    }
}