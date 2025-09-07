<?php

// use App\Http\Controllers\HomeController;
// use Illuminate\Support\Facades\Route;

// Route::get('/test-login', function() {
//     return view('auth.login-custom');
// });

// Auth::routes();

// Route::get('/home', [HomeController::class, 'index'])->name('home');
// Route::get('/login-custom', [HomeController::class, 'login'])->name('login.custom');

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// use Illuminate\Support\Facades\Mail;

Auth::routes();

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Test route for login page
Route::get('/test-login', function () {
    return view('auth.login-custom');
});

// Store captcha code in session
Route::post('/store-captcha', function (Request $request) {
    Session::put('captcha_code', $request->captcha);
    return response()->json(['status' => 'success']);
});

// Route::get('/test-mail', function () {
//     try {
//         Mail::raw('This is a test email from Laravel.', function ($message) {
//             $message->to('muhammadsyamim574313@gmail.com')
//                     ->subject('Test Email');
//         });
//         return 'Email sent!';
//     } catch (\Exception $e) {
//         return 'Error: ' . $e->getMessage();
//     }
// });

// Debug route to check session captcha (remove in production)
Route::get('/debug-captcha', function () {
    return "Current captcha in session: " . Session::get('captcha_code', 'NOT SET');
});

// In routes/web.php
Route::get('/check-users', function() {
    $users = DB::table('users')->get();
    echo "<pre>";
    print_r($users);
    echo "</pre>";
});

// Temporary debug route
Route::get('/debug-session', function() {
    echo "<pre>";
    echo "Session data:\n";
    print_r(session()->all());
    echo "\nOld input:\n";
    print_r(session()->get('_old_input', []));
    echo "\nErrors:\n";
    print_r(session()->get('errors', []));
    echo "</pre>";
});

// Debug route - check what's happening with validation
Route::get('/debug-validation', function(Request $request) {
    echo "<h2>Validation Debug</h2>";
    
    // Test the validation rules
    $testRequest = new \Illuminate\Http\Request([
        'username' => 'admin',
        'password' => 'wrongpassword',
        'captcha_input' => 'wrongcaptcha', 
        'captcha_session' => 'ABCDE'
    ]);
    
    try {
        $validator = Validator::make($testRequest->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
            'captcha_input' => 'required',
            'captcha_session' => 'required',
        ]);
        
        if ($validator->fails()) {
            echo "Validation fails with errors:<br>";
            foreach ($validator->errors()->all() as $error) {
                echo "- $error<br>";
            }
        } else {
            echo "Validation passes!<br>";
            
            // Test captcha validation
            if ($testRequest->captcha_input !== $testRequest->captcha_session) {
                echo "Captcha validation fails (as expected)<br>";
            } else {
                echo "Captcha validation passes<br>";
            }
        }
    } catch (\Exception $e) {
        echo "Exception: " . $e->getMessage();
    }
});

Route::get('/verify-password', function() {
    $user = DB::table('users')->where('username', 'admin')->first();
    if ($user) {
        $passwordMatch = Hash::check('admin', $user->password);
        echo "Password 'admin' matches hash: " . ($passwordMatch ? 'YES' : 'NO');
        return;
    }
    echo "User not found";
});

Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    // any other pages that require login
});

// Create admin user route (remove after use)
Route::get('/create-admin-user', function () {
    // Check if user already exists
    if (DB::table('users')->where('username', 'admin')->exists()) {
        return "Admin user already exists!";
    }

    // Create new user
    DB::table('users')->insert([
        'username' => 'admin',
        'name' => 'Administrator',
        'email' => 'admin@example.com',
        'password' => Hash::make('admin'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return "Admin user created successfully! Username: admin, Password: admin";
});

// Laravel authentication routes
Auth::routes();

// Application routes
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/login-custom', [HomeController::class, 'login'])->name('login.custom');

// Root route - redirect to custom login
Route::get('/', function () {
    return redirect('/login-custom');
});