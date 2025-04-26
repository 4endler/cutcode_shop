<?php

namespace Database\Factories;

use Domain\Catalog\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    protected $model = Brand::class;
    public function definition(): array
    {
        return [
            'title' => $this->faker->company(),
            'thumbnail' => $this->faker->fixturesImage('brands', 'brands'),
            'on_home_page' => $this->faker->boolean(),
            'rank'=>$this->faker->numberBetween(0, 100),
        ];
    }
}
