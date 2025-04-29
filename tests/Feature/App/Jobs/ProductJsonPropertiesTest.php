<?php

namespace Tests\Feature\App\Jobs;

use App\Jobs\ProductJsonProperties;
use Database\Factories\BrandFactory;
use Database\Factories\ProductFactory;
use Database\Factories\PropertyFactory;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ProductJsonPropertiesTest extends TestCase
{
    public function test_created_json_properties(): void
    {
        $queue = Queue::getFacadeRoot();
        Queue::fake([ProductJsonProperties::class]);
        
        $properties = PropertyFactory::new()->count(10)->create();

        BrandFactory::new()->create();
        $product = ProductFactory::new()
            ->hasAttached($properties, function(){
                return ['value' => fake()->word()];
            })
            ->create();

        $this->assertEmpty($product->json_properties);

        //Меняем очереди на реальные, чтоб зыполнить json_properties
        Queue::swap($queue);

        ProductJsonProperties::dispatchSync($product);
        
        $product->refresh();
        
        $this->assertNotEmpty($product->json_properties);
    }
}