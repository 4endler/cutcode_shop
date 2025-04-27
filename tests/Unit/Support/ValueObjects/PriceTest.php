<?php

namespace Tests\Unit\Support\ValueObjects;

use Support\ValueObjects\Price;
use Tests\TestCase;

class PriceTest extends TestCase
{
    public function test_all():void
    {
        $price = new Price(1000);
        $this->assertInstanceOf(Price::class, $price);
        $this->assertEquals(1000, $price->raw());
        $this->assertEquals(1000, $price->value());
        $this->assertEquals('RUB', $price->currency());
        $this->assertEquals('₽', $price->symbol());
        $this->assertEquals('1 000 ₽', $price);

        $this->expectException(\InvalidArgumentException::class);
        Price::make(-1);
        Price::make(1, 'USD');

    }
}