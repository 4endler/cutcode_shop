<?php

namespace Domain\Order\Processes;

use Domain\Order\Contracts\OrderProcessContract;
use Domain\Order\Models\Order;

final class AssignCustomer implements OrderProcessContract
{
    public function __construct(
        //TODO сделать dto
        protected array $customer,
    ) {}

    public function handle(Order $order, $next)
    {
        $order->orderCustomer()->create($this->customer);
        
        return $next($order);
    }
}