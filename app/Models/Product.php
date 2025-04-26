<?php

namespace App\Models;

use Support\Casts\PriceCast;
use Domain\Catalog\Models\Brand;
use Domain\Catalog\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Support\Traits\Models\HasSlug;
use Support\Traits\Models\HasThumbnail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;
    use HasSlug;
    use HasThumbnail;

    protected $fillable = [
        'slug', 'title','brand_id', 'thumbnail', 'price',
        'on_home_page', 'rank',
    ];

    public function scopeFiltered(Builder $query, array $filters) 
    {
        
    }
    public function scopeSorted(Builder $query, array $filters) 
    {

    }
    protected function thumbnailDir(): string
    {
        return 'products';
    }
    public function scopeHomePage(Builder $query)
    {
        $query->where('on_home_page', true)->orderBy('rank')->limit(8);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    protected $casts =[
        'price' => PriceCast::class
    ];
}
