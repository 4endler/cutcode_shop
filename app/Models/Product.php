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
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Pipeline\Pipeline;

class Product extends Model
{
    use HasFactory;
    use HasSlug;
    use HasThumbnail;

    protected $fillable = [
        'slug', 'title','brand_id', 'thumbnail', 'price',
        'on_home_page', 'rank',
    ];

    public function scopeFiltered(Builder $query) 
    {
        return app(Pipeline::class)
            ->send($query)
            ->through(filters())
            ->thenReturn();
    }
    public function scopeSorted(Builder $query) 
    {
        sorter()->run($query);
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

    public function properties(): BelongsToMany
    {
        return $this->BelongsToMany(Property::class)
            ->withPivot('value');
    }

    public function optionValues(): BelongsToMany
    {
        return $this->belongsToMany(OptionValue::class);
    }

    protected $casts =[
        'price' => PriceCast::class
    ];
}
