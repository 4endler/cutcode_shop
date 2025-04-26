<?php

namespace Domain\Catalog\Models;

use App\Models\Product;
use Database\Factories\BrandFactory;
use Domain\Catalog\QueryBuilders\BrandQueryBuilder;
use Support\Traits\Models\HasSlug;
use Support\Traits\Models\HasThumbnail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    use HasFactory;
    use HasSlug;
    use HasThumbnail;

    protected $fillable = [
        'slug', 'title', 'thumbnail',
        'on_home_page', 'rank',
    ];
    protected static function newFactory()
    {
        return BrandFactory::new();
    }
    protected function thumbnailDir(): string
    {
        return 'brands';
    }
    public function newEloquentBuilder($query): BrandQueryBuilder
    {
        return new BrandQueryBuilder($query);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}

