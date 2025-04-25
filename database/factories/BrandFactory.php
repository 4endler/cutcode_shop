<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{

    public function definition(): array
    {
        return [
            'title' => $this->faker->company(),
            'thumbnail' => $this->faker->fixturesImage('brands', 'images/brands'),
            'on_home_page' => $this->faker->boolean(),
            'rank'=>$this->faker->numberBetween(0, 100),
        ];
    }
}
