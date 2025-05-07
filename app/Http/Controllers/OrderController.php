<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderFormRequest;
use Domain\Order\Actions\NewOrderAction;
use Domain\Order\DTOs\NewOrderDTO;
use Domain\Order\Models\DeliveryType;
use Domain\Order\Models\PaymentMethod;
use Domain\Order\Processes\AssignCustomer;
use Domain\Order\Processes\AssignProducts;
use Domain\Order\Processes\ChangeStateToPending;
use Domain\Order\Processes\CheckProductQuantities;
use Domain\Order\Processes\ClearCart;
use Domain\Order\Processes\DecreaseProductsQuantities;
use Domain\Order\Processes\OrderProcess;
use DomainException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    public function index(): Factory|View|Application
    {
        $items = cart()->items();

        if($items->isEmpty()) {
            throw new DomainException('Корзина пуста');
        }

        return view('order.index',[
            'items'=>$items,
            //TODO по хорошему сделать select нужных полей
            'payments' => PaymentMethod::all(),
            'deliveries' => DeliveryType::all(),
        ]);
    }

    public function handle(OrderFormRequest $request, NewOrderAction $action): RedirectResponse
    {
        $order = $action(NewOrderDTO::fromRequest($request));
//TODO сделать опции к order_itemns и стоимость не  доделал
        (new OrderProcess($order))->processes([
            new CheckProductQuantities(),
            new AssignCustomer($request->validated('customer')),
            new AssignProducts(),
            new ChangeStateToPending(),
            new DecreaseProductsQuantities(),
            new ClearCart(),
        ])->run();
        return redirect()->route('home');
    }
}
