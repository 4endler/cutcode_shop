<?php

namespace Tests\Feature\App\Http\Controllers;

use Database\Factories\BrandFactory;
use Database\Factories\CategoryFactory;
use Database\Factories\ProductFactory;
use Domain\Catalog\Models\Brand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ThumbnailControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_generated_success(): void
    {
        Storage::fake('images');
        $size = '100x100';
        $ext = 'jpg'; // или другое исходное расширение

        $method = 'resize';

        $storage = Storage::disk('images');

        config()->set('thumbnail', ['allowed_sizes' => [$size]]);
        BrandFactory::new()->create();
        CategoryFactory::new()->create();
        $product = ProductFactory::new()->create();

        $response = $this->get($product->makeThumbnail($size, $method));

        $response->assertOk();

        $storage->assertExists(
            "products/$method/$size/$ext/" . File::name($product->thumbnail) . '.webp'
        );
    }
}