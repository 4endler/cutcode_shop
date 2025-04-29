<?php

namespace Tests\Feature\App\Http\Controllers;

use Database\Factories\BrandFactory;
use Database\Factories\CategoryFactory;
use Database\Factories\ProductFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_success_response(): void
    {
        BrandFactory::new()->create();
        $product = ProductFactory::new()->create();

        $this->get(route('product', $product))
            ->assertStatus(200)
            ->assertViewIs('product.show')
            ->assertViewHas('product');
    }
}