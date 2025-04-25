<?php

namespace Tests\Feature\Domain\Auth;

use App\Http\Controllers\Auth\ForgotPasswordController;
use Database\Factories\UserFactory;
use Domain\Auth\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ForgorPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_page_success(): void
    {
        $this->get(action([ForgotPasswordController::class, 'page']))
            ->assertStatus(200)
            ->assertSee('Забыли пароль?')
            ->assertViewIs('auth.forgot-password');
    }
 
    public function test_handle_success(): void
    {
        // Создаем пользователя
        $user = UserFactory::new()->create(['email' => 'test@mail.com']);

        // Мокаем сервис Password
        Password::shouldReceive('sendResetLink')
            ->once()
            ->with(['email' => $user->email])
            ->andReturn(Password::RESET_LINK_SENT);

        // Отправляем запрос
        $response = $this->post(route('forgot.handle'), [
            'email' => $user->email
        ]);

        // Проверяем ответ
        $response->assertValid();
        $response->assertRedirect();
        $response->assertSessionHas('shop_flash_message');
    }

    public function test_handle_with_invalid_email(): void
    {
        $response = $this->post(route('forgot.handle'), [
            'email' => 'invalid_email@example.com'
        ]);

        $response->assertInvalid(['email']);
        $response->assertSessionHasErrors('email');
    }

    public function test_handle_with_empty_email(): void
    {
        $response = $this->post(route('forgot.handle'), [
            'email' => ''
        ]);
        
        $response->assertInvalid(['email']);
        $response->assertSessionHasErrors('email');
    }

    public function test_handle_with_invalid_email_format(): void
    {
        $response = $this->post(route('forgot.handle'), [
            'email' => 'invalid_email'
        ]);
        
        $response->assertInvalid(['email']);
        $response->assertSessionHasErrors('email');
    }

    public function test_handle_when_reset_link_not_sent(): void
    {
        $user = UserFactory::new()->create(['email' => 'test@mail.com']);

        Password::shouldReceive('sendResetLink')
            ->once()
            ->with(['email' => $user->email])
            ->andReturn(Password::INVALID_USER);

        $response = $this->post(route('forgot.handle'), [
            'email' => $user->email
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
    }

    public function test_handle_with_throttle_protection(): void
    {
        $user = UserFactory::new()->create(['email' => 'test@mail.com']);

        // Делаем 5 запросов подряд (имитируя превышение лимита)
        for ($i = 0; $i < 5; $i++) {
            $this->post(route('forgot.handle'), ['email' => $user->email]);
        }

        // 6-й запрос должен получить ошибку throttle
        $response = $this->post(route('forgot.handle'), [
            'email' => $user->email
        ]);

        $response->assertStatus(429); // Too Many Requests
    }
}