<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient; // kalau data kes disimpan dalam table patients
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Kalau admin, terus redirect ke dashboard admin
        if (strtolower($user->role) === 'admin') {
            return redirect()->route('admin.home');
        }

        // Kalau user biasa → kekal di home.blade.php
        $year = Carbon::now()->year;
        $month = Carbon::now()->month;

        // Kira jumlah kes tahun ini
        $totalCasesYear = Patient::whereYear('created_at', $year)->count();

        // Kira jumlah kes bulan ini
        $totalCasesMonth = Patient::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count();

        return view('home', compact('totalCasesYear', 'totalCasesMonth'));
    }

    public function login()
    {
        return view('auth.login-custom');
    }
}
