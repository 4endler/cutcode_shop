<?php

namespace Database\Factories;

use Domain\Catalog\Models\Brand;
use Domain\Product\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Domain\Product\Models\Product;>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;
    public function definition(): array
    {
        return [
            'title' => ucfirst($this->faker->words(2, true)),
            'text' => $this->faker->paragraphs(rand(1, 5), true),
            'brand_id' => Brand::query()->inRandomOrder()->first()->id,
            'thumbnail' => $this->faker->fixturesImage('products', 'products'),
            'price' => $this->faker->numberBetween(100, 10000),
            'on_home_page' => $this->faker->boolean(),
            'rank'=>$this->faker->numberBetween(0, 100),
            'quantity'=>$this->faker->numberBetween(0, 20),
        ];
    }
}
