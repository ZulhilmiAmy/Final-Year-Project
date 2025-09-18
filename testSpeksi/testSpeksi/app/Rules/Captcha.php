<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Session;

class Captcha implements Rule
{
    public function passes($attribute, $value)
    {
        // Get the captcha from session and the user input
        $sessionCaptcha = Session::get('captcha_code');
        
        // Clear the session captcha after validation attempt
        Session::forget('captcha_code');
        
        return $value === $sessionCaptcha;
    }

    public function message()
    {
        return 'The captcha is incorrect.';
    }
}