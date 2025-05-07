<?php

namespace Domain\Payment\Exceptions;

class PaymentProviderException extends \Exception
{
    public static function providerRequired(): self
    {
        return new self('Provider is required');
    }
}