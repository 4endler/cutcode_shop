<?php

namespace Domain\Order\Models;

use Domain\Product\Models\OptionValue;
use Domain\Product\Models\Product;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Support\Casts\PriceCast;
use Support\ValueObjects\Price;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
       'product_id','price','quantity',
    ];

    protected $casts = [
        'price' => PriceCast::class,
    ];
    
    public function amount(): Attribute
    {
        return Attribute::make(
            get: fn() => Price::make($this->price->raw() * $this->quantity),
        );
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    public function optionValues(): BelongsToMany
    {
        return $this->belongsToMany(OptionValue::class, 'order_item_option_value');
    }
    
}
