<?php

namespace Domain\Payment\Gateways;

use Domain\Payment\Contracts\PaymentGatewayContract;
use Domain\Payment\Exceptions\PaymentProviderException;
use Domain\Payment\PaymentData;
use Exception;
use Illuminate\Http\JsonResponse;
use Support\ValueObjects\Price;
use Throwable;
use YouKassa\Client;

class YouKassa implements PaymentGatewayContract
{
    //Пример с курса
    protected Client $client;

    protected PaymentData $paymentData;

    protected string $errorMessage;

    public function __construct(array $config)//в config
    {
        $this->client = new Client();
        $this->configure($config);
    }
    public function paymentId():string
    {
        return $this->paymentData->id;
    }
    public function configure(array $config): void
    {
        $this->client->setAuth(...$config);
    }

    public function data(PaymentData $paymentData): self 
    {
        $this->paymentData = $paymentData;
        return $this;
    }

    public function request(): mixed
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    public function response(): JsonResponse 
    {
        try {
            $response = $this->client
                ->capturePayment(
                    $this->payload(),
                    $this->paymentObject()->getId(),
                    $this->idempotencyKey()
                );
        } catch (Throwable $e) {
            $this->errorMessage = $e->getMessage();

            throw new PaymentProviderException($e->getMessage());
        }

        return response()->json($response);
    }

    public function url(): string
    {
        try {
            $response = $this->client->createPayment(
                $this->payload(),
                $this->idempotencyKey()
            );
            
            return $response->getConfirmation()->getConfirmationUrl();
            
        } catch (Exception $e) {
            throw new PaymentProviderException($e->getMessage());
        }
    }

    public function validate(): bool
    {
        $meta = $this->paymentObject()->getMetadata()->toArray();

        $this->data(new PaymentObject(
            $this->paymentObject()->getId(),
            $this->paymentObject()->getDescription(),
            Price::make(
                $this->paymentObject()->getAmount()->getIntegerValue(),
                $this->paymentObject()->getAmount()->getCurrency()
            ),
            collect($meta)
        ));
        
        return $this->paymentObject()->getStatus() === PaymentStatus::WAITING_FOR_CAPTURE;
    }

    public function paid(): bool
    {
        return $this->paymentObject()->getPaid();
    }

    public function errorMessage(): string
    {
        return $this->errorMessage;
    }

    public function payload(): array
    {
        return [
            'amount' => [
                'value' => $this->paymentData->amount->value(),
                'currency' => $this->paymentData->amount->currency(),
            ],
            'confirmation' => [
                'type' => 'redirect',
                'return_url' => $this->paymentData->returnUrl,
            ],
            'description' => $this->paymentData->description,
            'reciept' => [
                'items' => [
                    [
                        'quantity' => 1,
                        'amount' => [
                            'value' => $this->paymentData->amount->value(),
                            'currency' => $this->paymentData->amount->currency(),
                        ],
                        'vat_code' => 1,
                        'description' => $this->paymentData->description,
                        'payment_subject' => 'intellectual_activity',
                        'payment_mode' => 'full_prepayment',
                        
                    ]
                ],
                'tax_system_code' => 1,
                'email' => $this->paymentData->meta->get('email'),
            ],
            'metadata' => $this->paymentData->meta->toArray(),
        ];
    }
    
    public function paymentObject(): PaymentResponse|Payment|PaymentInterface
    {
        $request = $this->request();

        try {
           $notification = ($request['event'] === NotificationEventType::PAYMENT_SUCCEEDED)
                ? new NotificationSucceeded($request)
                : new NotificationWaitingForCapture($request);
        } catch (Exception $e) {
            $this->errorMessage = $e->getMessage();

            throw new PaymentProviderException($e->getMessage());
        }
        return $notification->getObject();        
    }

    public function idempotencyKey(): string
    {
        return uniqid('', true);
    }
    
}