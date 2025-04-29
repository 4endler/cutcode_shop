<?php

namespace App\Jobs;

use Domain\Product\Models\Product;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProductJsonProperties implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Product $product
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // $properties = $this->product->properties->keyValues();
        $properties = $this->product->getFreshProperties();

        $this->product->updateQuietly(['json_properties' => $properties]);
    }

    public function uniqueId(): string
    {
        return $this->product->getKey();
    }
}
