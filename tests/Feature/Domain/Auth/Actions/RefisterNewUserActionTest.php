<?php

namespace Tests\Feature\Domain\Auth\Actions;

use Domain\Auth\Contracts\RegisterNewUserContract;
use Domain\Auth\DTOs\NewUserDTO;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class RefisterNewUserActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_success_user_created(): void
    {
        $this->assertDatabaseMissing('users', ['email' => 'email@mail.ru']);

        $action = app(RegisterNewUserContract::class);
        $action(new NewUserDTO('name', 'email@mail.ru', 'password'));

        $this->assertDatabaseHas('users', ['email' => 'email@mail.ru']);
    }
}