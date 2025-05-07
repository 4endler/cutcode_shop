<?php

namespace App\Http\Controllers;

use Domain\Cart\Models\CartItem;
use Domain\Favorites\FavoriteManager;
use Domain\Favorites\Models\Favorite;
use Domain\Favorites\Models\FavoriteItem;
use Domain\Product\Models\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class FavoriteController extends Controller
{
    public function index(): Factory|View|Application
    {
        //TODO: реализовать
        return view('favorites.index',[
            'items'=>favorites()->favoriteItems()
        ]);
    }

    public function add(Product $product): RedirectResponse
    {
        app(FavoriteManager::class)->add($product);

        flash()->info('Товар добавлен в избранное');

        return redirect()->back();
    }

    public function delete(FavoriteItem $item): RedirectResponse
    {
        favorites()->delete($item);
        flash()->info('Удален товар из избранного');

        return redirect()->back();
            // ->intended(route('favorites'));
    }
    public function truncate(): RedirectResponse
    {
        FavoriteManager::make()->truncate();
        flash()->info('Избранное очищено');

        return redirect()
            ->intended(route('favorites'));
    }
}
