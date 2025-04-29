<?php

namespace Tests\Feature\Controllers;

use Database\Factories\BrandFactory;
use Database\Factories\CategoryFactory;
use Database\Factories\ProductFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_shows_catalog_page_with_products()
    {
        BrandFactory::new()->count(2)->create();
        CategoryFactory::new()->count(2)->create();
        $product =  ProductFactory::new()->create();

        $response = $this->get(route('catalog'));

        $response->assertOk()
            ->assertViewIs('catalog.index')
            ->assertViewHas('products')
            ->assertSee($product->title)
            ;
    }

    public function test_filters_products_by_category()
    {
        BrandFactory::new()->count(2)->create();
        $category = CategoryFactory::new()->create();
        $productInCategory = ProductFactory::new()
            ->hasAttached($category)
            ->create();
        $productNotInCategory = ProductFactory::new()->create();

        $response = $this->get(route('catalog', $category));

        $response->assertOk()
            ->assertViewHas('products', function ($products) use ($productInCategory, $productNotInCategory) {
                return $products->contains($productInCategory) && 
                       !$products->contains($productNotInCategory);
            });
    }

    public function test_shows_only_categories_with_products()
    {
        BrandFactory::new()->count(2)->create();
        $categoryWithProducts = CategoryFactory::new()
            ->has(ProductFactory::new()->count(2))
            ->create();
        $emptyCategory = CategoryFactory::new()->create();

        $response = $this->get(route('catalog'));

        $response->assertViewHas('categories', function ($categories) use ($categoryWithProducts, $emptyCategory) {
            return $categories->contains($categoryWithProducts) && 
                   !$categories->contains($emptyCategory);
        });
    }

    public function test_applies_sorting()
    {
        BrandFactory::new()->count(2)->create();
        ProductFactory::new()->create(['title' => 'B Product', 'price' => 200]);
        ProductFactory::new()->create(['title' => 'A Product', 'price' => 100]);

        // Тест сортировки по цене (возрастание)
        $this->get(route('catalog', ['sort' => 'price']))
            ->assertSeeInOrder(['A Product', 'B Product']);

        // Тест сортировки по цене (убывание)
        $this->get(route('catalog', ['sort' => '-price']))
            ->assertSeeInOrder(['B Product', 'A Product']);

        // Тест сортировки по названию
        $this->get(route('catalog', ['sort' => 'title']))
            ->assertSeeInOrder(['A Product', 'B Product']);
    }

    public function test_paginates_products()
    {
        BrandFactory::new()->count(2)->create();
        ProductFactory::new()->count(10)->create();

        $response = $this->get(route('catalog'));

        $response->assertViewHas('products', function ($products) {
            return $products->count() === 6; // Проверяем пагинацию
        });
    }

    public function test_returns_only_required_fields()
    {
        BrandFactory::new()->count(2)->create();
        ProductFactory::new()->create();

        $response = $this->get(route('catalog'));
        // $response->assertDontSee('text'); // Проверяем отсутствие поля 'text'
        $response->assertDontSee('created_at'); // Проверяем отсутствие поля 'created_at'
        $response->assertDontSee('updated_at'); // Проверяем отсутствие поля 'updated_at'
    }
    public function test_applies_filters()
    {
        BrandFactory::new()->count(2)->create();
        $expensiveProduct = ProductFactory::new()->create(['price' => 1000]);
        $cheapProduct = ProductFactory::new()->create(['price' => 100]);

        $this->get(route('catalog', ['filters' => ['price' => ['from' => 500]]]))
            ->assertSee($expensiveProduct->title)
            ->assertDontSee($cheapProduct->title);
    }

    //TODO
    //Тест для поиска товаров не заработал
    // public function test_searches_products_by_fulltext()
    // {
    //     BrandFactory::new()->count(2)->create();
    //     // 1. Создаем тестовые товары // Так, потому что кириллические символы не работают
    //     $product0 = ProductFactory::new()->create(['title' => 'Unique Notebook', 'price' => 1000]);
    //     $product1 = ProductFactory::new()->create([
    //         'title' => 'Unique note for you',
    //         'text' => 'Powerfull device'
    //     ]);
    //     $product2 = ProductFactory::new()->create([
    //         'title' => 'Unique device for you',
    //         'text' => 'Powerfull note'
    //     ]);
        
    //     $product3 = ProductFactory::new()->create([
    //         'title' => 'some device',
    //         'text' => 'Camera for you'
    //     ]);

    //     // 2. Выполняем запрос с поиском
    //     $response = $this->get(route('catalog', ['s' => 'Notebook']));
    //     // 3. Проверяем результаты
    //     $response
    //         ->assertOk()
    //         ->assertViewIs('catalog.index')
    //         ->assertViewHas('products');
    //         dd($response->getContent());
    //     $this->get(route('catalog', ['s' => 'Notebook']))
    //         ->assertSee($product0->title);
    //     // Проверяем что найден нужный товар
    //     // $response->assertSee($product1->title);
    //     // $response->assertSee('Unique');
    //     // $response->assertSee($product2->title);
        
    //     // Проверяем что ненужный товар не отображается
    //     // $response->assertDontSee($product3->title);
    // }

    // //Тест пустого поиска:
    // public function test_handles_empty_search()
    // {
    //     BrandFactory::new()->count(2)->create();
    //     $product = ProductFactory::new()->create();
        
    //     $response = $this->get(route('catalog', ['s' => '']));
        
    //     $response->assertOk()
    //         ->assertSee($product->title);
    // }

    // //Тест специальных символов:
    // public function it_handles_special_chars_in_search()
    // {
    //     BrandFactory::new()->count(2)->create();
    //     $product = ProductFactory::new()->create([
    //         'title' => 'Товар с "специальными" символами'
    //     ]);
        
    //     $response = $this->get(route('catalog', ['s' => '"специальными"']));
        
    //     $response->assertOk()
    //         ->assertSee($product->title);
    // }

    // //Тест с пагинацией:
    // public function test_searches_with_pagination()
    // {
    //     BrandFactory::new()->count(2)->create();
    //     // Создаем 15 товаров, содержащих слово "ноутбук"
    //     ProductFactory::new()->count(15)->create([
    //         'title' => fn() => 'Ноутбук ' . fake()->words(2, true)
    //     ]);
        
    //     $response = $this->get(route('catalog', ['s' => 'ноутбук']));
        
    //     $response->assertOk()
    //         ->assertViewHas('products', function ($products) {
    //             return $products->count() === 6; // Проверяем пагинацию
    //         });
    // }
}