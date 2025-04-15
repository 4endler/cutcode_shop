<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordFormRequest;
use App\Http\Requests\ResetPasswordFormRequest;
use App\Http\Requests\SignInFormRequest;
use App\Http\Requests\SignUpFormRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    public function index()
    {
        return view('auth.index');
    }
    public function signUp()
    {
        return view('auth.sign-up');
    }
    public function signIn(SignInFormRequest $request): RedirectResponse
    {
        if (!Auth::attempt($request->validated())) {
            return back()->withErrors([
                'email'=>'Неправильный логин или пароль'
            ])->onlyInput('email');
        }
        
        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    public function store(SignUpFormRequest $request): RedirectResponse
    {
        $user = User::query()->create($request->validated());

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('home');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken(); 

        return redirect()->route('home');
    }

    public function forgot()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(ForgotPasswordFormRequest $request) 
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );
        //TODO 3rd lesson message
        return $status === Password::RESET_LINK_SENT
                    ? back()->with(['message' => __($status)])
                    : back()->withErrors(['email' => __($status)]);
    }

    public function resetPassword(string $token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }
    public function updatePassword(ResetPasswordFormRequest $request) 
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));
                
                $user->save();

                event(new PasswordReset($user));
            }
        );
        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('message', __($status))
                    : back()->withErrors(['email' => [__($status)]]);
    }
}
