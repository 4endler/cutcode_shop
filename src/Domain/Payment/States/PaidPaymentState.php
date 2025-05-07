<?php

namespace Domain\Payment\States;

final class PaidPaymentState extends PaymentState
{
    public static string $name = 'paid';
}