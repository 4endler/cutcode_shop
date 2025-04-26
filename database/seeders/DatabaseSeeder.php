<?php

namespace Database\Seeders;


use App\Models\Product;
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
        Category::factory(20)
            ->has(Product::factory(rand(1,2)))
            ->create();
    }
}
