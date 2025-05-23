<?php

namespace App\Http\Controllers;

use Domain\Cart\Models\CartItem;
use Domain\Product\Models\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(): Factory|View|Application
    {
        return view('cart.index',[
            'items'=>cart()->cartItems()
        ]);
    }

    public function add(Product $product): RedirectResponse
    {
        cart()->add(
            $product, 
            request('quantity', 1),
            request('options',[])
        );
        flash()->info('Товар добавлен в корзину');

        return redirect()
            ->intended(route('cart'));
    }
    public function quantity(CartItem $item): RedirectResponse
    {
        if (request('quantity') <= 0) {
            flash()->alert('Количество товара не может быть меньше или равно 0');
            return redirect()
                ->intended(route('cart'));
        }
        cart()->quantity(
            $item,
            request('quantity', 1)
        );  
        flash()->info('Количество товара изменено');

        return redirect()
            ->intended(route('cart'));
    }
    public function delete(CartItem $item): RedirectResponse
    {
        cart()->delete($item);
        flash()->info('Удален товар из корзины');

        return redirect()
            ->intended(route('cart'));
    }
    public function truncate(): RedirectResponse
    {
        cart()->truncate();
        flash()->info('Корзина очищена');

        return redirect()
            ->intended(route('cart'));
    }
}
