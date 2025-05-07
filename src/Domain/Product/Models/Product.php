<?php

namespace Domain\Product\Models;

use App\Jobs\ProductJsonProperties;
use Support\Casts\PriceCast;
use Domain\Catalog\Models\Brand;
use Domain\Catalog\Models\Category;
use Domain\Product\Collections\PropertyCollection;
use Domain\Product\QueryBuilders\ProductQueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Support\Traits\Models\HasSlug;
use Support\Traits\Models\HasThumbnail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

class Product extends Model
{
    use HasFactory;
    use HasSlug;
    use HasThumbnail;

    protected $fillable = [
        'slug', 'title','brand_id', 'thumbnail', 'price',
        'on_home_page', 'rank','json_properties','quantity'
    ];

    protected $casts =[
        'price' => PriceCast::class,
        'json_properties' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($product) {
            //Товар создается но проперти еще нет, поэтому они пустые. Поэтому через job
            ProductJsonProperties::dispatch($product)
                ->delay(now()->addSeconds(10));
        });
        static::updated(function ($product) {
            ProductJsonProperties::dispatch($product);
        });
    }

    protected function thumbnailDir(): string
    {
        return 'products';
    }
    public function newEloquentBuilder($query): ProductQueryBuilder
    {
        return new ProductQueryBuilder($query);
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
        return $this->belongsToMany(Property::class)
            ->withPivot('value');
    }

    public function optionValues(): BelongsToMany
    {
        return $this->belongsToMany(OptionValue::class);
    }
    public function getFormattedPropertiesAttribute()
    {
        if (empty($this->json_properties)) {
            // Если свойства не закешированы, получаем их из БД
            return $this->getFreshProperties();
        }
    
        return $this->json_properties;
    }
    
    public function getFreshProperties()
    {
        return $this->properties()->get()->keyValues();
    }

}
