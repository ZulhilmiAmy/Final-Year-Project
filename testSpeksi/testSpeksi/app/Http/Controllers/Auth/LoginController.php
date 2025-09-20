<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    // Tell Laravel to use 'username' instead of the default 'email'
    public function username()
    {
        return 'username';
    }

    // Add captcha validation
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
            'captcha_input' => 'required|string',
            'captcha_session' => 'required|string',
        ]);

        if ($request->input('captcha_input') !== $request->input('captcha_session')) {
            throw ValidationException::withMessages([
                'captcha_input' => ['Captcha tidak sah.'],
            ]);
        }
    }

    protected function redirectTo()
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return '/admin/home'; // admin → homeAdmin.blade.php
        }

        return '/home'; // user biasa → home.blade.php
    }
}
