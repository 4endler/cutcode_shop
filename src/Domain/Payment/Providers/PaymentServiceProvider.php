<?php

namespace Domain\Payment\Providers;

use Domain\Payment\Models\Payment;
use Domain\Payment\PaymentSystem;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        //Например 
        //PaymentSystem::provider(new YouKassa(config('payment.providers.youkassa')))
        //или
        // PaymentSystem::provider(function() {
        //     if (request()->has('uniptay')) {
        //         return Unitpay();
        //     }
            
        //     return YouKassa();
        // });


        PaymentSystem::onCreating(function(PaymentData $paymentData) {
            return $paymentData;
        });

        PaymentSystem::onSuccess(function(Payment $payment) {
            //ИМожно тут отправлять уведомления и прочее
            $payment->update([
                'state' => PaymentState::PAID,
            ]);
        });
    }

    public function register(): void
    {
        // $this->app->register(
        //     ActionsServiceProvider::class
        // );
    }
}
