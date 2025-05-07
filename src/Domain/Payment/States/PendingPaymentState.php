<?php

namespace Domain\Payment\States;

final class PendingPaymentState extends PaymentState
{
    public static string $name = 'pending';
}