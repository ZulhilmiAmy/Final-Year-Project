<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\CaseModel;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // public function index()
    // {
    //     $year = Carbon::now()->year;
    //     $month = Carbon::now()->month;

    //     // Jumlah kes tahun ini
    //     $totalCasesYear = Patient::whereYear('created_at', $year)->count();

    //     // Jumlah kes bulan ini
    //     $totalCasesMonth = Patient::whereYear('created_at', $year)
    //         ->whereMonth('created_at', $month)
    //         ->count();

    //     return view('admin.homeAdmin', compact('totalCasesYear', 'totalCasesMonth'));
    // }

    public function totalCase()
    {
        $year = \Carbon\Carbon::now()->year;

        // Jumlah kes setahun
        $totalCasesYear = \App\Models\Patient::whereYear('created_at', $year)->count();

        // Semua kes ikut bulan
        $casesByMonth = \App\Models\Patient::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.totalCase', compact('totalCasesYear', 'casesByMonth'));
    }
    public function homeAdmin()
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        $totalCasesYear = Patient::whereYear('created_at', $currentYear)->count();
        $totalCasesMonth = Patient::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->count();

        return view('admin.homeAdmin', compact('totalCasesYear', 'totalCasesMonth'));
    }

}
