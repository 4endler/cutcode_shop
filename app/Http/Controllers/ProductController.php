<?php

namespace App\Http\Controllers;

use Domain\Product\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    protected const LAST_SEEN_COUNT = 5;
    public function __invoke(Product $product)
    {
        //добавляем опцию, чтобы не заргужать отдельно
        $product->load('optionValues.option');

        $this->addToSeenProducts($product->id);

        return view('product.show', [
            'product' => $product,
            'options' => $product->optionValues->keyValues(),
            'seenProducts' => $this->getSeenProducts($product->id)
        ]);
    }

    protected function addToSeenProducts(int $productId): void
    {
        $seen = session()->get('seen_products', []);

        // Удаляем ID если он уже есть (чтобы не было дублей)
        $seen = array_diff($seen, [$productId]);
        
        // Добавляем в начало массива
        array_unshift($seen, $productId);
        
        // Ограничиваем 4 элементами
        $seen = array_slice($seen, 0, self::LAST_SEEN_COUNT);
        
        session()->put('seen_products', $seen);
    }

    protected function getSeenProducts($currentProductId = null): Collection
    {
        $seenIds = session()->get('seen_products', []);
        
        if (empty($seenIds)) {
            return collect();
        }
        //TODO сделать сброс кеша при обновлении или удалении товаров
        $cacheKey = $this->generateSeenProductsCacheKey($seenIds, $currentProductId);

        return Cache::rememberForever($cacheKey, function() use ($seenIds, $currentProductId) {
            $products = Product::query()->whereIn('id', $seenIds);
    
            if ($currentProductId) {
                $products->where('id', '!=', $currentProductId);
            }
    
            return $products
                ->select('id', 'title', 'thumbnail', 'price', 'slug')
                ->orderByRaw('FIELD(id, ' . implode(',', $seenIds) . ')')
                ->limit(self::LAST_SEEN_COUNT)
                ->get();
        });
    }

    protected function generateSeenProductsCacheKey(array $seenIds, $currentProductId = null): string
    {
        $idsString = implode('_', $seenIds);
        $current = $currentProductId ? "_exclude_{$currentProductId}" : '';
        return "seen_products:{$idsString}{$current}";
    }
}
