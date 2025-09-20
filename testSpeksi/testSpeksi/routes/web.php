<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Debug & Testing Routes (hapus bila production)
|--------------------------------------------------------------------------
*/
Route::get('/test-login', fn() => view('auth.login-custom'));
Route::post('/store-captcha', function (Request $request) {
    Session::put('captcha_code', $request->captcha);
    return response()->json(['status' => 'success']);
});

// Untuk surat ikut IC
Route::get('/patients/letter/ic/{no_kp}', [PatientController::class, 'letterByNoKp'])
    ->name('patients.letter.ic')
    ->middleware('auth');

// Dalam group → ikut ID (default resource binding)
Route::get('/patients/letter/{patient}', [PatientController::class, 'letter'])
    ->name('patients.letter')
    ->middleware('auth');


/*
|--------------------------------------------------------------------------
| Protected Routes (hanya boleh akses bila login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'prevent-back-history'])->group(function () {
    // Home
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // ✅ Page daftar user baru
    Route::get('/admin/register-user', function () {
        return view('admin.registerUser'); // view file: resources/views/admin/registerUser.blade.php
    })->name('admin.registerUser');

    Route::get('/admin/home', [DashboardController::class, 'homeAdmin'])->name('admin.home');

    // untuk Dashboard
    // Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/dashboard', [DashboardController::class, 'homeAdmin'])->name('admin.dashboard');

    Route::get('/admin/total-case', [DashboardController::class, 'totalCase'])->name('admin.totalCase');

    // ✅ Proses simpan user baru
    Route::post('/admin/register-user', [App\Http\Controllers\Auth\RegisterController::class, 'register'])
        ->name('admin.registerUser.store');

    // Patients custom routes (mesti atas resource)
    Route::get('/patients/search', [PatientController::class, 'search'])->name('patients.search');
    Route::get('/patients/history', [PatientController::class, 'history'])->name('patients.history');
    Route::get('/patients/letter/{patient}', [PatientController::class, 'letter'])->name('patients.letter');
    // Patients - Create Page ikut role
    Route::get('/patients/create', [PatientController::class, 'create'])->name('patients.create');

    // Route::get('/patients/{patient}', [PatientController::class, 'show'])->name('patients.show');

    // Patients CRUD (resource route)
    Route::resource('patients', PatientController::class);

    // Appointments
    Route::get('/appointments/upcoming', [AppointmentController::class, 'upcoming'])
        ->name('appointments.upcoming');
});

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/
// Custom login page
Route::get('/login-custom', [HomeController::class, 'login'])->name('login.custom');

// Override default /login ke custom login
Route::redirect('/login', '/login-custom');

// Root → redirect ke login
Route::get('/', fn() => redirect('/login-custom'));

// Laravel Auth scaffolding (register, login, logout, dll.)
Auth::routes();
