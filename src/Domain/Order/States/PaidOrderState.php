<?php

namespace Domain\Order\States;

final class PaidOrderState extends OrderState
{
    protected array $allowedTransitions = [
        DeliveredOrderState::class,
        CancelledOrderState::class,
    ];


    public function canBeChanged(): bool
    {
        return true;
    }

    public function value(): string
    {
        return 'paid';
    }
    public function humanValue(): string
    {
        return 'Оплачен';
    }

}
    