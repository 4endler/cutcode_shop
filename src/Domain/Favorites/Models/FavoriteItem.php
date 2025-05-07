<?php

namespace Domain\Favorites\Models;

use Domain\Product\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FavoriteItem extends Model
{
    protected $fillable = [
        'favorite_id',
        'product_id',
    ];


    public function favorite():BelongsTo
    {
        return $this->belongsTo(Favorite::class);
    }
    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

