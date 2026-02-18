<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated extends RedirectIfAuthenticated
{
    /**
     * Get the path the user should be redirected to when they are authenticated.
     */
    protected function redirectTo($request)
    {
        return route('dashboard');
    }
}
