<?php

namespace Domain\Catalog\ViewModels;

use Domain\Catalog\Models\Brand;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Support\Traits\Makeable;

final class BrandViewModel
{
    use Makeable;

    public function homePage(): Collection|array
    {
        return Cache::rememberForever('brands_home_page', function () {
            return Brand::query()
                ->homePage()
                ->get();
        });
    }
}