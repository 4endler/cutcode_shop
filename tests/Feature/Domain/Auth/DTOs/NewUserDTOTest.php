<?php

namespace Domain\Auth\DTOs;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class NewUserDTOTest extends TestCase
{
    use RefreshDatabase;
    public function test_instance_created(): void
    {
        $newUserDto = new NewUserDTO('test', 'IcEj0@mail', 'password');
        $this->assertInstanceOf(NewUserDTO::class, $newUserDto);
    }
}