<?php

namespace Domain\Order\Processes;

use Domain\Cart\Models\CartItem;
use Domain\Order\Contracts\OrderProcessContract;
use Domain\Order\Models\Order;

final class AssignProducts implements OrderProcessContract
{

    public function handle(Order $order, $next)
    {
        cart()->items()->each(function(CartItem $item) use ($order) {
            $orderItem = $order->orderItems()->create([
                'product_id' => $item->product_id,
                'price' => $item->price,
                'quantity' => $item->quantity,
            ]);
            
            if ($item->optionValues->isNotEmpty()) {
                $orderItem->optionValues()->sync($item->optionValues);
            }
        });
        
        return $next($order);
    }
    
    protected function formatOptionsForPivot(CartItem $item): array
    {
        return $item->optionValues->map(function($optionValue) {
            return [
                'option_id' => $optionValue->option_id,
                'option_value_id' => $optionValue->id,
                'value' => $optionValue->value,
            ];
        })->toArray();
    }
}