<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignUpFormRequest;
use Domain\Auth\Contracts\RegisterNewUserContract;
use Domain\Auth\DTOs\NewUserDTO;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class SignUpController extends Controller
{
    public function page()
    {
        return view('auth.sign-up');
    }

    public function handle(SignUpFormRequest $request, RegisterNewUserContract $action): RedirectResponse
    {
        //make DTO чтобы передавать в RegisterNewUserAction не массив а dto
        $dto = new NewUserDTO(
            $request->get('name'),
            $request->get('email'),
            $request->get('password')
        );
        $action($dto);
        //Auth::login($user);
        return redirect()->intended(route('home'));
    }
}
