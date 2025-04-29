<?php

namespace Database\Factories;

use Domain\Cart\Models\Cart;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cart>
 */
class CartFactory extends Factory
{
    protected $model = Cart::class;
    public function definition(): array
    {
        return [
            //
        ];
    }
}
