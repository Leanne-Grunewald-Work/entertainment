<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RedirectToHome implements LoginResponseContract, RegisterResponseContract
{
    public function toResponse($request)
    {
        return redirect()->intended('/');
    }
}
