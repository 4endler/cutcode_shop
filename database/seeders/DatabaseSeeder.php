<?php

namespace Database\Seeders;

use Database\Factories\CategoryFactory;
use Domain\Product\Models\Product;
use Database\Factories\OptionFactory;
use Database\Factories\OptionValueFactory;
use Database\Factories\ProductFactory;
use Database\Factories\PropertyFactory;
use Domain\Catalog\Models\Brand;
use Domain\Catalog\Models\Category;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Brand::factory(20)->create();
        $properties = PropertyFactory::new()->count(10)->create();

        OptionFactory::new()->count(3)->create();
        $optionValues = OptionValueFactory::new()->count(10)->create();
        CategoryFactory::new()->count(10)
            ->has(ProductFactory::new()->count(rand(2,3))
                ->hasAttached($optionValues)
                ->hasAttached($properties, function(){
                        return [
                            'value' => ucfirst(fake()->word())
                        ];
                    })
            )
            ->create();
    }
    
}
