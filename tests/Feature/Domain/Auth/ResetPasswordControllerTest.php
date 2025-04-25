<?php

namespace Tests\Feature\Domain\Auth;

use App\Http\Controllers\Auth\ResetPasswordController;
use Database\Factories\UserFactory;
use Domain\Auth\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ResetPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_page_success(): void
    {
        // 1. Создаем тестового пользователя
        $user = UserFactory::new(['email' => 'test@mail.com'])->create();
                
        // 2. Генерируем токен сброса пароля
        $token = Password::createToken($user);

        // 3. Вызываем маршрут с токеном
        $response = $this->get(action([ResetPasswordController::class, 'page'], $token));
        // dd(action([AuthController::class, 'resetPassword'], $token));
        // 4. Проверяем ответ
        $response->assertOk();
        $response->assertViewIs('auth.reset-password');
        $response->assertViewHas('token', $token);
    }
    public function test_handle_success(): void
    {
        Event::fake();
        // 1. Создаем тестового пользователя
        $user = UserFactory::new(['email' => 'test@mail.com','password' => Hash::make('old-password'),])->create();
            
        // 2. Генерируем токен сброса пароля
        $token = Password::createToken($user);

        $request = [
            'token' => $token,
            'email' => $user->email,
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ];

        $response = $this->post(action([ResetPasswordController::class, 'handle'], $request));

        $response->assertRedirect(route('login'));

        // Проверяем, что пароль был обновлен
        $this->assertTrue(Hash::check('new_password', $user->refresh()->password));

        // Проверяем, что было отправлено событие PasswordReset
        Event::assertDispatched(PasswordReset::class);

        $response->assertSessionHas('shop_flash_message');
    }

    public function test_handle_with_invalid_token(): void
    {
        $user = UserFactory::new(['email' => 'test@mail.com'])->create();
        $response = $this->post(action([ResetPasswordController::class, 'handle'], [
            'token' => 'invalid_token',
            'email' => $user->email,
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ]));
        
        $response->assertRedirect();
        $response->assertSessionHasErrors(['email' => __('passwords.token')]);
    }

    public function test_handle_with_invalid_email(): void
    {
        $user = UserFactory::new(['email' => 'test@mail.com'])->create();
        $token = Password::createToken($user);
        
        $response = $this->post(action([ResetPasswordController::class, 'handle']), [
            'token' => $token,
            'email' => 'nonexistent@mail.com',
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['email']);
    }

    public function test_handle_with_password_mismatch(): void
    {
        $user = UserFactory::new(['email' => 'test@mail.com'])->create();
        $token = Password::createToken($user);
        
        $response = $this->post(action([ResetPasswordController::class, 'handle']), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'new_password',
            'password_confirmation' => 'different_password',
        ]);

        $response->assertInvalid(['password']);
    }



    public function test_handle_with_empty_fields(): void
    {
        $response = $this->post(action([ResetPasswordController::class, 'handle']), [
            'token' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertInvalid(['token', 'email', 'password']);
    }

}