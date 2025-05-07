<?php

namespace Domain\Payment\States;

final class CancelledPaymentState extends PaymentState
{
    public static string $name = 'cancelled';
}