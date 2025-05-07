<?php

namespace Domain\Order\Actions;

use Domain\Auth\Actions\RegisterNewUserAction;
use Domain\Auth\DTOs\NewUserDTO;
use Domain\Order\DTOs\NewOrderDTO;
use Domain\Order\Models\Order;

final class NewOrderAction
{
    //Передаем не OrderFormRequest, а Dto
    public function __invoke(NewOrderDTO $dto): Order
    {
        $registerAction = app(RegisterNewUserAction::class);

        $customer = $dto->customer;
        
        if ($dto->create_account) {
            $registerAction(NewUserDTO::make(
                $customer['first_name'] . ' ' . $customer['last_name'],
                $customer['email'],
                $dto->password,
            ));
        }
        
        return Order::create([
            // 'customer_id' => $customer->id,
            'delivery_type_id' => $dto->delivery_type_id,
            'payment_method_id' => $dto->payment_method_id,
        ]);
    }
}