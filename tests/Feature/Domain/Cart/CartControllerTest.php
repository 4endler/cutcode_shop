<?php

namespace Tests\Feature\Domain\Cart;

use App\Http\Controllers\CartController;
use Database\Factories\BrandFactory;
use Database\Factories\ProductFactory;
use Domain\Cart\CartManager;
use Domain\Product\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        CartManager::fake();
    }
    protected function createProduct(): Product
    {
        BrandFactory::new()->create();
        ProductFactory::new()->create();
        return Product::first();
    }
    public function test_is_empty_cart(): void
    {
        $this->get(action([CartController::class, 'index']))
            ->assertOk()
            ->assertViewIs('cart.index')
            ->assertViewHas('items', collect([]))
            ->assertSee('Корзина пуста');
    }
    public function test_is_not_empty_cart(): void
    {
        $product = $this->createProduct();
        cart()->add($product);
        $this->get(action([CartController::class, 'index']))
            ->assertOk()
            ->assertViewIs('cart.index')
            ->assertViewHas('items', cart()->items());
    }

    public function test_added_succes(): void
    {
        $product = $this->createProduct();
        $this->assertEquals(0, cart()->count());

        $response = $this->post(action([CartController::class, 'add'], $product), [
            'quantity' => 4,
        ]);
        $this->assertEquals(4, cart()->count());
        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
        ]);
        $response->assertSessionHas('shop_flash_message');
    }
    public function test_quantity_changed(): void
    {
        $product = $this->createProduct();
        cart()->add($product, 4);
        $this->assertEquals(4, cart()->count());

        $response = $this->post(action([CartController::class, 'quantity'], cart()->items()->first()), [
            'quantity' => 6,
        ]);
        $this->assertEquals(6, cart()->count());

        $response->assertSessionHas('shop_flash_message');
    }
    public function test_delete_item_success(): void
    {
        $product = $this->createProduct();
        cart()->add($product, 4);
        $this->assertEquals(4, cart()->count());

        $response = $this->delete(action([CartController::class, 'delete'], cart()->items()->first()));
        $this->assertEquals(0, cart()->count());

        $response->assertSessionHas('shop_flash_message');
    }
    public function test_truncate_success(): void
    {
        $product = $this->createProduct();
        cart()->add($product, 4);
        $this->assertEquals(4, cart()->count());

        $response = $this->delete(action([CartController::class, 'truncate']));
        $this->assertEquals(0, cart()->count());

        $response->assertSessionHas('shop_flash_message');
    }
    public function test_add_invalid_quantity(): void
    {
        $product = $this->createProduct();
        cart()->add($product, 4);
        $response = $this->post(action([CartController::class, 'add'], $product), [
            'quantity' => 0, // или отрицательное число
        ]);
        $response->assertSessionHas('shop_flash_message');
    }
}
