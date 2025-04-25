<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignInFormRequest;
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
        if (!Auth::attempt($request->validated())) {
            return back()->withErrors([
                'email'=>'Неправильный логин или пароль'
            ])->onlyInput('email');
        }
        
        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }


    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken(); 

        return redirect()->route('home');
    }
}
