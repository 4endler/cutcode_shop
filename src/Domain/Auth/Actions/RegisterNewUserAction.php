<?php

namespace Domain\Auth\Actions;

use Domain\Auth\Contracts\RegisterNewUserContract;
use Domain\Auth\DTOs\NewUserDTO;
use Domain\Auth\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

final class RegisterNewUserAction implements RegisterNewUserContract
{
    public function __invoke(NewUserDTO $data)
    {
        $user = User::query()->create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => bcrypt($data->password),
        ]);

        event(new Registered($user));

        Auth::login($user);
    }
}