<?php

namespace Domain\Order\DTOs;

use App\Http\Requests\OrderFormRequest;
use Support\Traits\Makeable;

final class NewOrderDTO
{
    use Makeable;

    public function __construct(
        public readonly array $customer,
        public readonly bool $create_account,
        public readonly ?string $password,
        public readonly int $delivery_type_id,
        public readonly int $payment_method_id,
    ) {

    }

    public static function fromRequest(OrderFormRequest $request): NewOrderDTO
    {
        return self::make(
            customer: $request->validated('customer'),
            create_account: $request->boolean('create_account'), // false, если чекбокса нет
            password: $request->filled('create_account') ? $request->validated('password') : null,
            delivery_type_id: $request->validated('delivery_type_id'),
            payment_method_id: $request->validated('payment_method_id'),
        );
    }
}