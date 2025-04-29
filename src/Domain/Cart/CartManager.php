<?php

namespace Domain\Cart;

use Domain\Cart\Contracts\CartIdentityStorageContract;
use Domain\Cart\Models\Cart;
use Domain\Cart\Models\CartItem;
use Domain\Product\Models\Product;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Support\ValueObjects\Price;

final class CartManager
{
    public function __construct(
        protected CartIdentityStorageContract $identityStorage
    )
    {
    }
    private function cacheKey(): string
    {
        return str('cart_' . $this->identityStorage->get())
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
    private function stringedOptionValues(array $optionValues = []): string
    {
        sort($optionValues);
        return implode(';',$optionValues);
    }
    public function add(Product $product, int $quantity = 1, array $optionValues = []): Model|Builder
    {
        $cart = Cart::query()
            ->updateOrCreate([
                'storage_id' => $this->identityStorage->get()
            ],  $this->storedData($this->identityStorage->get()));
        
        $cartItem = $cart->cartItems()->updateOrCreate([
            'product_id' => $product->getKey(),
            'string_option_values' => $this->stringedOptionValues($optionValues)
        ], [
            'price' => $product->price, 
            'quantity' => DB::raw("quantity + $quantity"),
            'string_option_values' => $this->stringedOptionValues($optionValues)
        ]);

        $cartItem->optionValues()->sync($optionValues);

        $this->forgetCache();

        return $cart;
    }

    public function quantity(CartItem $item, int $quantity = 1):void
    {
        $item->update([
            'quantity' => $quantity
        ]);

        $this->forgetCache();
    }

    public function delete(CartItem $item):void
    {
        $item->delete();

        $this->forgetCache();
    }

    public function truncate():void
    {
        $this->get()?->delete();

        $this->forgetCache();
    }


    public function items(): Collection
    {
        if (!$this->get()) {
            return collect([]);
        }
        return CartItem::query()
            ->with(['product:id','optionValues.option'])
            ->whereBelongsTo($this->get())
            ->get();
    }
    
    public function cartItems(): Collection
    {
        if ($this->get() === false) {
            return collect([]);
        }

        return Cache::remember(
            $this->cacheKey() . '_items',
            now()->addHour(),
            fn() => $this->get()->cartItems()
                ->with(['product', 'optionValues.option'])
                ->get()
        );
    }

    public function count():int
    {
        return $this->cartItems()->sum(function($item) {
            return $item->quantity;
        });
    }

    public function amount(): Price
    {
        return Price::make(
            $this->cartItems()->sum(function($item) {
                return $item->amount->raw();
            })
        );
    }
    public function get()
    {
        return Cache::remember($this->cacheKey(), now()->addHour(), function(){
            return Cart::query()
            // ->with('cartItems')
            ->where('storage_id', $this->identityStorage->get())
            ->when(Auth::check(), fn(Builder $query) => $query->orWhere('user_id',Auth::id()))
            ->first() ?? false;
            //если cart query возвращает null (когда корзина пустая) и null в кеш не сохраняется,
            //поэтому чтобы не дергать постоянно запросы на пустую корзину возвращаем false
            //false в кеш сохраняется
        });
    }
}