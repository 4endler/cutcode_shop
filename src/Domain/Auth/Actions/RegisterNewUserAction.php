<?php

namespace Domain\Auth\Actions;

use Domain\Auth\Contracts\RegisterNewUserContract;
use Domain\Auth\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

final class RegisterNewUserAction implements RegisterNewUserContract
{
    public function __invoke(array $data)
    {
        $user = User::query()->create($data);

        event(new Registered($user));

        Auth::login($user);
    }
}