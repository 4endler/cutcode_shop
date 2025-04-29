<?php

namespace Database\Factories;

use Domain\Cart\Models\CartItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CartItem>
 */
class CartItemFactory extends Factory
{

    protected $model = CartItem::class;
    public function definition(): array
    {
        return [
            //
        ];
    }
}
