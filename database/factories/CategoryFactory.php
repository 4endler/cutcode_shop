<?php

namespace Database\Factories;

use Domain\Catalog\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;
    public function definition(): array
    {
        return [
            'title' => ucfirst($this->faker->words(2, true)),
            'on_home_page' => $this->faker->boolean(),
            'rank'=>$this->faker->numberBetween(0, 100),
        ];
    }
}
