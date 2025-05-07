<?php

namespace Domain\Payment\Gateways;

use Domain\Payment\Contracts\PaymentGatewayContract;

class UnitPay implements PaymentGatewayContract
{
    public function paymentId(): string
    {
        return 'unitpay';
    }

    public function configure(array $config): void
    {
    }

    public function data(array $data): array 
    {
        return [];
    }

    public function request(): array
    {
        return [];
    }

    public function response(array $data): array
    {
        return [];
    }

    public function url(): string
    {
        return '';
    }

    public function validate(array $data): bool
    {
        return false;
    }

    public function paid(array $data): bool 
    {
        return false;
    }

    public function errorMessage(): string
    {
        return '';
    }
    }
    
    
}