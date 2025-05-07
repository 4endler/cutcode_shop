<?php

namespace App\Http\Controllers;

use Domain\Payment\PaymentData;
use Domain\Payment\PaymentSystem;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Collection;
use Support\ValueObjects\Price;

class PurchaseController extends Controller
{
    public function index():Redirector|Application|RedirectResponse
    {
        return redirect(
            PaymentSystem::create(new PaymentData(
                //Передать все необходимые данные
                id: '1',
                description: 'Покупка товара',
                returnUrl: route('purchase.callback'),
                amount: new Price(100),
                meta: new Collection(),
            ))->url()
        );
    }

    public function callback():JsonResponse
    {
        return PaymentSystem::validate()->response();
    }
}
