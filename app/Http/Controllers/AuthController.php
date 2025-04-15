<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignInFormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
