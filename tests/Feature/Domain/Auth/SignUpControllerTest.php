<?php

namespace Tests\Feature\Domain\Auth;

use App\Http\Controllers\Auth\SignUpController;
use App\Listeners\SendEmailNewUserListener;
use App\Notifications\NewUserNotification;
use Database\Factories\UserFactory;
use Domain\Auth\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class SignUpControllerTest extends TestCase
{
    use RefreshDatabase;

    protected array $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = [
            'name'=> 'test',
            'email' => 'IcEj0@mail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];
        
    }

    private function request(): TestResponse
    {
        return $this->post(action([SignUpController::class, 'handle']), $this->request);
    }
    public function test_signup_page_success(): void
    {
        $this->get(action([SignUpController::class, 'page']))
            ->assertStatus(200)
            ->assertSee('Регистрация')
            ->assertViewIs('auth.sign-up');
    }

    public function test_handle_success(): void
    {
        Notification::fake();
        Event::fake();

        $this->assertDatabaseMissing('users', ['email' => $this->request['email']]);
        
        $response = $this->request();
        $response->assertValid();
        $this->assertDatabaseHas('users', ['email' => $this->request['email']]);

        Event::assertDispatched(Registered::class);
        Event::assertListening(Registered::class, SendEmailNewUserListener::class);

        //Вызываем events вручную, чтобы проверить отправку уведомления
        $user = User::query()->where('email', $this->request['email'])->first();
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
        $this->assertEquals(Auth::id(), $user->id);

        $response->assertRedirect(route('home'));
    }
    public function test_handle_empty_name() : void
    {
        $this->request['name'] = '';
        
        $response = $this->request();

        $response->assertInvalid(['name']);
    }
    public function test_handle_empty_email() : void
    {
        $this->request['email'] = '';
        
        $response = $this->request();

        $response->assertInvalid(['email']);
    }
    public function test_handle_empty_password() : void
    {
        $this->request['password'] = '';
        
        $response = $this->request();

        $response->assertInvalid(['password']);
    }
    public function test_handle_empty_confirm_password() : void
    {
        $this->request['password_confirmation'] = '';
        
        $response = $this->request();

        $response->assertInvalid(['password']);
    }
    public function test_handle_email_not_valid() : void
    {
        $this->request['email'] = 'not-valid-email';
        
        $response = $this->request();

        $response->assertInvalid(['email']);
    }
    public function test_handle_email_exist() : void
    {
        $user = UserFactory::new()->create([
            'email' => 'test@mail.com',
            'password' => bcrypt('password'),
        ]);
        $this->request['email'] = $user->email;
        
        $response = $this->request();

        $response->assertInvalid(['email']);
        
    }
    public function test_handle_password_invalid() : void
    {
        $this->request['password'] = 'pas';
        $this->request['password_confirmation'] = 'pas';
        
        $response = $this->request();

        $response->assertInvalid(['password']);
    }
    public function test_handle_confirm_password_invalid() : void
    {
        $this->request['password_confirmation'] = 'password-not valid';
        
        $response = $this->request();

        $response->assertInvalid(['password']);
    }
}