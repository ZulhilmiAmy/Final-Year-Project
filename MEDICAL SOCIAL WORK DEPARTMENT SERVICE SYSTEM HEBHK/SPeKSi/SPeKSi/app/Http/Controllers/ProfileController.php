<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Update the user’s password
        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('status', 'Kata laluan berjaya dikemas kini.');
    }
}
