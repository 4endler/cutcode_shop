<?php

namespace Domain\Favorites\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Favorite extends Model
{
    use MassPrunable;
    protected $fillable = [
        'storage_id',
        'user_id',
    ];

    public function favoriteItems():HasMany
    {
        return $this->hasMany(FavoriteItem::class);
    }

    public function prunable(): Builder
    {
        return static::where('created_at', '<', now()->subMonth());
    }
    
}
