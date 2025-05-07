<?php

namespace Domain\Catalog\Observers;

use Domain\Catalog\Models\Brand;
use Illuminate\Support\Facades\Cache;

class BrandObserver
{
    public function saved(Brand $brand): void
    {
        Cache::forget('brands_home_page');
    }

    public function deleted(Brand $brand): void
    {
        Cache::forget('brands_home_page');
    }

    // Если нужно сбрасывать и при восстановлении (для мягкого удаления)
    public function restored(Brand $brand): void
    {
        Cache::forget('brands_home_page');
    }
}
