<?php

namespace Domain\Order\Enums;

use Domain\Order\Models\Order;
use Domain\Order\States\OrderState;
use Domain\Order\States\NewOrderState;
use Domain\Order\States\PendingOrderState;
use Domain\Order\States\PaidOrderState;
use Domain\Order\States\DeliveredOrderState;
use Domain\Order\States\CancelledOrderState;

enum OrderStatuses: string
{
    case New = 'new';
    case Pending = 'pending';
    case Paid = 'paid';
    case Delivered = 'delivered';
    case Canceled = 'canceled';

    public function createState(Order $order): OrderState
    {
        return match ($this) {
            self::New => new NewOrderState($order),
            self::Pending => new PendingOrderState($order),
            self::Paid => new PaidOrderState($order),
            self::Delivered => new DeliveredOrderState($order),
            self::Canceled => new CancelledOrderState($order),
        };
    }
}