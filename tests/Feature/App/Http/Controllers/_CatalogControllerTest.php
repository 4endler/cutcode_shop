<?php

namespace Tests\Feature\App\Http\Controllers;

use Database\Factories\BrandFactory;
use Database\Factories\CategoryFactory;
use Database\Factories\ProductFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_success(): void
    {
        BrandFactory::new()->count(4)->create([
            'on_home_page' => true
        ]);
        CategoryFactory::new()->count(4)->create();
        ProductFactory::new()->count(4)->create([
            'on_home_page' => true
        ]);
        

        $this->get(route('home'))
            ->assertStatus(200)
            ->assertViewIs('homepage')
            ->assertViewHas('products')
            ->assertViewHas('categories')
            ->assertViewHas('brands');
    }
}