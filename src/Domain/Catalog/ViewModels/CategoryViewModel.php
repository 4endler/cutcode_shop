<?php

namespace Domain\Catalog\ViewModels;

use Domain\Catalog\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Support\Traits\Makeable;

final class CategoryViewModel
{
    use Makeable;
    
    public function homePage(): Collection|array
    {
        return Cache::rememberForever($this->cacheKey(), function () {
            return Category::query()
                ->homePage()
                ->get();
        });
    }
    
    public function cacheKey(): string
    {
        return 'categories.home_page.forever.v1'; // Версионный ключ
    }
}