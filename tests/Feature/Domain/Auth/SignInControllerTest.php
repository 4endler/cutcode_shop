<?php

namespace Tests\Feature\Domain\Auth;

use App\Http\Controllers\Auth\SignInController;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class SignInControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_page_success(): void
    {
        $this->get(action([SignInController::class, 'page']))
            ->assertStatus(200)
            ->assertSee('Вход в аккаунт')
            ->assertViewIs('auth.login');
    }
 
    public function test_handle_success(): void
    {
        $password = 'password';
        $user = UserFactory::new()->create([
            'email' => 'IcEj0@mail.com',
            'password' => bcrypt($password),
        ]);
        $response = $this->post(action([SignInController::class, 'handle'], [
            'email' => $user->email,
            'password' => $password,
        ]));
        $response
            ->assertValid()
            ->assertRedirect(route('home'));

        $this->assertEquals(Auth::id(), $user->id);

    }

    public function test_logout_success(): void
    {
        $password = 'password';
        $user = UserFactory::new()->create([
            'email' => 'IcEj0@mail.com',
            'password' => bcrypt($password),
        ]);

        $this->actingAs($user)->delete(action([SignInController::class, 'logout']));
        $this->assertGuest();
    }

    public function test_handle_with_invalid_password(): void
    {
        $user = UserFactory::new()->create([
            'email' => 'IcEj0@mail.com',
            'password' => bcrypt('password'),
        ]);
        $response = $this->post(action([SignInController::class, 'handle'], [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]));
        $response->assertInvalid(['email']);
        $this->assertGuest();
    }
    public function test_handle_with_invalid_email(): void
    {
        $user = UserFactory::new()->create([
            'email' => 'IcEj0@mail.com',
            'password' => bcrypt('password'),
        ]);
        $response = $this->post(action([SignInController::class, 'handle'], [
            'email' => 'wrong-email',
            'password' => 'password',
        ]));
        $response->assertInvalid(['email']);
        $this->assertGuest();
    }
    public function test_handle_with_empty_fields(): void
    {
        $response = $this->post(action([SignInController::class, 'handle'], [
            'email' => '',
            'password' => '',
        ]));
        $response->assertInvalid(['email', 'password']);
        $this->assertGuest();
    }
    public function test_handle_with_invalid_email_format(): void
    {
        $response = $this->post(action([SignInController::class, 'handle'], [
            'email' => 'invalid_email',
            'password' => 'password',
        ]));
        $response->assertInvalid(['email']);
        $this->assertGuest();
    }
    public function test_handle_with_empty_password(): void
    {
        $user = UserFactory::new()->create([
            'email' => 'IcEj0@mail.com',
            'password' => bcrypt('password'),
        ]);
        $response = $this->post(action([SignInController::class, 'handle'], [
            'email' => $user->email,
            'password' => '',
        ]));
        $response->assertInvalid(['password']);
        $this->assertGuest();
    }
    public function test_handle_with_empty_email(): void
    {
        $response = $this->post(action([SignInController::class, 'handle'], [
            'email' => '',
            'password' => 'password',
        ]));
        $response->assertInvalid(['email']);
        $this->assertGuest();
    }
    public function test_handle__with_throttle_protection(): void
    {
        $user = UserFactory::new()->create([
            'email' => 'IcEj0@mail.com',
            'password' => bcrypt('password'),
        ]);
        for ($i = 0; $i < 5; $i++) {
            $this->post(action([SignInController::class, 'handle'], [
                'email' => $user->email,
                'password' => 'password_wrong',
            ]));
        }
        $response = $this->post(action([SignInController::class, 'handle'], [
            'email' => $user->email,
            'password' => 'password',
        ]));
        $response->assertStatus(429); 
        $this->assertGuest();
    }
    public function test_logout_guest(): void
    {
        $this->delete(action([SignInController::class, 'logout']));
        // $this->expectException();
        $this->assertGuest();
    }
}