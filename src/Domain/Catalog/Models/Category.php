<?php

namespace Domain\Catalog\Models;

use Database\Factories\CategoryFactory;
use Domain\Catalog\QueryBuilders\CategoryQueryBuilder;
use Domain\Product\Models\Product;
use Support\Traits\Models\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = [
        'slug', 'title',
        'on_home_page', 'rank',
    ];

    protected static function newFactory()
    {
        return CategoryFactory::new();
    }
    public function newEloquentBuilder($query): CategoryQueryBuilder
    {
        return new CategoryQueryBuilder($query);
    }
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
