<?php

namespace Domain\Favorites;

use Domain\Cart\Models\Cart;
use Domain\Cart\Models\CartItem;
use Domain\Cart\StorageIdentities\FakeSessionIdentityStorage;
use Domain\Favorites\Contracts\FavoriteIdentityStorageContract;
use Domain\Favorites\Models\Favorite;
use Domain\Favorites\Models\FavoriteItem;
use Domain\Product\Models\Product;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Support\Traits\Makeable;
use Support\ValueObjects\Price;

final class FavoriteManager
{
    use Makeable;
    public function __construct(
        protected FavoriteIdentityStorageContract $identityStorage
    )
    {
    }

    public static function fake(): void
    {
        app()->bind(FavoriteIdentityStorageContract::class, FakeSessionIdentityStorage::class);
    }
    private function cacheKey(): string
    {
        return str('favorites_' . $this->identityStorage->get())
            ->slug('_')
            ->value();
    }
    private function forgetCache(): void
    {
        Cache::forget($this->cacheKey());
        Cache::forget($this->cacheKey() . '_items');
    }
    private function storedData(string $id): array
    {
        $data = [
            'storage_id' => $id
        ];
        if (Auth::check()) {
            $data['user_id'] = Auth::id();
        }
        return $data;
    }
  
    public function add(Product $product): Model|Builder
    {
        $favorite = Favorite::query()
            ->updateOrCreate([
                'storage_id' => $this->identityStorage->get()
            ],  $this->storedData($this->identityStorage->get()));

        if($favorite->favoriteItems()->where('product_id', $product->getKey())->exists()){
            return $favorite;
        }
        $favorite->favoriteItems()->create([
            'product_id' => $product->getKey(),
        ]);

        $this->forgetCache();

        return $favorite;
    }


    public function delete(FavoriteItem $item):void
    {
        $item->delete();

        $this->forgetCache();
    }

    public function truncate():void
    {
        if ($this->get()) {
            $this->get()->delete();
        }

        $this->forgetCache();
    }


    public function items(): Collection
    {
        if (!$this->get()) {
            return collect([]);
        }
        return FavoriteItem::query()
            ->with(['product:id,title,slug,thumbnail,quantity'])
            ->whereBelongsTo($this->get())
            ->get();
    }
    
    public function favoriteItems(): Collection
    {
        if ($this->get() === false) {
            return collect([]);
        }

        return Cache::remember(
            $this->cacheKey() . '_items',
            now()->addHour(),
            fn() => $this->get()->favoriteItems()
                ->with(['product'])
                ->get()
        );
    }


    public function get()
    {
        return Cache::remember($this->cacheKey(), now()->addHour(), function(){
            $favorite = Favorite::query();

            if (Auth::check()) {
                $favorite->where('user_id', Auth::id());
            } else {
                $favorite->where('storage_id', $this->identityStorage->get());
            }
            return $favorite->first() ?? false;
            //если favorite query возвращает null (когда корзина пустая) и null в кеш не сохраняется,
            //поэтому чтобы не дергать постоянно запросы на пустую корзину возвращаем false
            //false в кеш сохраняется
        });
    }

    public function updateStorageId(string $oldId, string $newId):void
    {
        // dd($oldId, $newId, $this->storedData($newId));
        Favorite::query()
            ->where('storage_id', $oldId)
            ->update($this->storedData($newId));
        
    }

    public function isFavorite(Product $product):bool
    {
        return $this->favoriteItems()->contains('product_id', $product->getKey());
    }
}