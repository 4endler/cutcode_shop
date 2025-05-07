<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignInFormRequest;
use Support\SessionRegenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class SignInController extends Controller
{
    public function page()
    {
        // flash()->info('FSLSAKSDK');
        // return redirect()->route('home');
        return view('auth.login');
    }
    public function handle(SignInFormRequest $request): RedirectResponse
    {   
        $credentials = $request->validated();
        // $remember = $request->has('remember');
        
        // Проверяем credentials без автоматического логина, чтобы не изменять сессию
        if (!Auth::validate($credentials)) {
            return back()->withErrors([
                'email' => 'Неправильный логин или пароль'
            ])->onlyInput('email');
        }
        
        // Получаем пользователя
        $user = Auth::getProvider()->retrieveByCredentials($credentials);
        // dd($user);
        // Кастомная обработка сессии
        SessionRegenerator::run(function() use ($user) {
            Auth::login($user);
        });


        return redirect()->intended(route('home'));
    }


    public function logout(Request $request): RedirectResponse
    {
        SessionRegenerator::run(function() {
            Auth::logout();
        });

        return redirect()->route('home');
    }
}
