<?php

namespace Domain\Payment\Exceptions;

class PaymentProcessException extends \Exception
{
    public static function paymentNotFound(): self
    {
        return new self('Payment not found');
    }
}