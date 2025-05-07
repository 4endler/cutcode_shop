<?php

namespace Domain\Payment;

use Closure;
use Domain\Payment\Contracts\PaymentGatewayContract;
use Domain\Payment\Exceptions\PaymentProcessException;
use Domain\Payment\Exceptions\PaymentProviderException;
use Domain\Payment\Models\Payment;
use Domain\Payment\Models\PaymentHistory;
use Domain\Payment\States\CancelledPaymentState;
use Domain\Payment\States\PaidPaymentState;
use Domain\Payment\Traits\PaymentEvents;

final class PaymentSystem
{
    use PaymentEvents;
    protected static PaymentGatewayContract $provider;

    public static function provider(PaymentGatewayContract|Closure $providerOrClosure): void
    {
        if (is_callable($providerOrClosure)) {//Если у нас передана функция, то вызываем её
            $providerOrClosure = call_user_func($providerOrClosure);
        }

        if (!$providerOrClosure instanceof PaymentGatewayContract) {
            throw PaymentProviderException::providerRequired();
        }

        static::$provider = $providerOrClosure;
    }
    
    public static function create(PaymentData $paymentData):PaymentGatewayContract
    {
        if (!self::$provider instanceof PaymentGatewayContract) {
            throw PaymentProviderException::providerRequired();
        }
//TODO dto?
        Payment::create([
            'payment_id' => $paymentData->id,
            'payment_gateway' => get_class(self::$provider),
            'amount' => $paymentData->amount->value(),
            'meta' => $paymentData->meta,
        ]);
        if (is_callable(self::$onCreating)) {
            $paymentData = call_user_func(self::$onCreating, $paymentData);
        }

        return self::$provider->data($paymentData);
    }

    public static function validate(): PaymentGatewayContract
    {
        if (!self::$provider instanceof PaymentGatewayContract) {
            throw PaymentProviderException::providerRequired();
        }

        //Пишем себе логи, потому что от эквайринга можно их не получить
        PaymentHistory::query()->create([
            'method' => request()->method(),
            'payload' => self::$provider->request(),
            'payment_gateway' => get_class(self::$provider),
        ]);

        if (is_callable(self::$onValidating)) {
            call_user_func(self::$onValidating);
        }

        if (self::$provider->validate() && self::$provider->paid()) {
            try {
                $payment = Payment::query()
                    ->where('payment_id', self::$provider->paymentId())
                    ->firstOr(function(){
                        throw PaymentProcessException::paymentNotFound();
                    });

               if (is_callable(self::$onSuccess)) {
                call_user_func(self::$onSuccess, $payment);
               }

               $payment->state->transitionTo(PaidPaymentState::class);
            } catch (PaymentProcessException $e) {
                if (is_callable(self::$onError)) {
                    call_user_func(
                        self::$onError,
                        self::$provider->errorMessage() ?? $e->getMessage()
                    );
                }

                $payment->state->transitionTo(CancelledPaymentState::class);
            }
        }

        return self::$provider;
    }
    
    

    
}