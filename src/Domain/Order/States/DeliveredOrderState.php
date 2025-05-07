<?php

namespace Domain\Order\States;

final class DeliveredOrderState extends OrderState
{
    protected array $allowedTransitions = [

    ];


    public function canBeChanged(): bool
    {
        return false;
    }

    public function value(): string
    {
        return 'delivered';
    }
    public function humanValue(): string
    {
        return 'Доставлен';
    }

}
    