<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Password::defaults(function () {
            // Minimum policy: at least 8 chars
            // Add .letters()->mixedCase()->numbers()->symbols() if you want stronger rules
            return Password::min(8)
                ->letters()       // must contain letters
                ->mixedCase()     // must have UPPERCASE and lowercase
                ->numbers()       // must contain numbers
                ->symbols()       // must contain symbols
                ->uncompromised(); // not found in known data breaches
        });
    }
}
