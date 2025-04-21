<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Http\Controllers\AuthController;
use App\Listeners\SendEmailNewUserListener;
use App\Models\User;
use App\Notifications\NewUserNotification;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_success(): void
    {
        $this->get(action([AuthController::class, 'index']))
            ->assertStatus(200)
            ->assertSee('Вход в аккаунт')
            ->assertViewIs('auth.index');
    }
    public function test_signup_page_success(): void
    {
        $this->get(action([AuthController::class, 'signUp']))
            ->assertStatus(200)
            ->assertSee('Регистрация')
            ->assertViewIs('auth.sign-up');
    }
    public function test_forgot_page_success(): void
    {
        $this->get(action([AuthController::class, 'forgot']))
            ->assertStatus(200)
            ->assertSee('Забыли пароль?')
            ->assertViewIs('auth.forgot-password');
    }
    public function test_signin_success(): void
    {
        $password = 'password';
        $user = User::factory()->create([
            'email' => 'IcEj0@mail.com',
            'password' => bcrypt($password),
        ]);
        $response = $this->post(action([AuthController::class, 'signIn'], [
            'email' => $user->email,
            'password' => $password,
        ]));
        $response
            ->assertValid()
            ->assertRedirect(route('home'));

        $this->assertEquals(auth()->id(), $user->id);

    }
    public function test_store_success(): void
    {
        Notification::fake();
        Event::fake();

        $request = [
            'name'=> 'test',
            'email' => 'IcEj0@mail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $this->assertDatabaseMissing('users', ['email' => $request['email']]);
        
        $response = $this->post(action([AuthController::class, 'store']), $request);
        $response->assertValid();
        $this->assertDatabaseHas('users', ['email' => $request['email']]);

        Event::assertDispatched(Registered::class);
        Event::assertListening(Registered::class, SendEmailNewUserListener::class);

        //Вызываем events вручную, чтобы проверить отправку уведомления
        $user = User::query()->where('email', $request['email'])->first();
        $event = new Registered($user);
        $listener = new SendEmailNewUserListener();
        $listener->handle($event);
        Notification::assertSentTo(
            $user,
            NewUserNotification::class
        );

        // Проверяем, что пользователь аутентифицирован
        $this->assertAuthenticated(); // без параметров - просто проверяем факт аутентификации
        
        // ИЛИ если нужно проверить конкретного пользователя:
        $this->assertEquals(auth()->id(), $user->id);

        $response->assertRedirect(route('home'));
    }
    public function test_logout_success(): void
    {
        $password = 'password';
        $user = User::factory()->create([
            'email' => 'IcEj0@mail.com',
            'password' => bcrypt($password),
        ]);

        $this->actingAs($user)->delete(action([AuthController::class, 'logout']));
        $this->assertGuest();
    }
    public function test_send_reset_link_email_success(): void
    {
        // Создаем пользователя
        $user = User::factory()->create(['email' => 'test@mail.com']);

        // Мокаем сервис Password
        Password::shouldReceive('sendResetLink')
            ->once()
            ->with(['email' => $user->email])
            ->andReturn(Password::RESET_LINK_SENT);

        // Отправляем запрос
        $response = $this->post(route('password.email'), [
            'email' => $user->email
        ]);

        // Проверяем ответ
        $response->assertValid();
        $response->assertRedirect();
        $response->assertSessionHas('shop_flash_message');
    }
    public function test_reset_password_success(): void
    {
        // 1. Создаем тестового пользователя
        $user = User::factory(['email' => 'test@mail.com'])->create();
                
        // 2. Генерируем токен сброса пароля
        $token = Password::createToken($user);

        // 3. Вызываем маршрут с токеном
        $response = $this->get(action([AuthController::class, 'resetPassword'], $token));
        // dd(action([AuthController::class, 'resetPassword'], $token));
        // 4. Проверяем ответ
        $response->assertOk();
        $response->assertViewIs('auth.reset-password');
        $response->assertViewHas('token', $token);
    }
    public function test_update_password_success(): void
    {
        Event::fake();
        // 1. Создаем тестового пользователя
        $user = User::factory(['email' => 'test@mail.com','password' => Hash::make('old-password'),])->create();
            
        // 2. Генерируем токен сброса пароля
        $token = Password::createToken($user);

        $request = [
            'token' => $token,
            'email' => $user->email,
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ];

        $response = $this->post(action([AuthController::class, 'updatePassword'], $request));

        $response->assertRedirect(route('login'));

        // Проверяем, что пароль был обновлен
        $this->assertTrue(Hash::check('new_password', $user->refresh()->password));

        // Проверяем, что было отправлено событие PasswordReset
        Event::assertDispatched(PasswordReset::class);

        $response->assertSessionHas('shop_flash_message');
    }
}