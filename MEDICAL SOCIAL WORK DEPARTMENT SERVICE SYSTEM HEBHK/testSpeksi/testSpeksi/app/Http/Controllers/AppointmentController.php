<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient; // guna patients table
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function upcoming()
    {
        $today = Carbon::today();

        $appointments = Patient::whereDate('tarikh_temu', '>=', $today)
            ->orderBy('tarikh_temu', 'asc')
            ->take(10)
            ->get();

        // transform untuk sesuai dgn Calendar + Reminder List
        $formatted = $appointments->map(function ($patient) {
            return [
                'id'           => $patient->id,
                'patient_name' => $patient->nama,      // untuk reminder list
                'date'         => $patient->tarikh_temu, // untuk reminder list
                'title'        => $patient->nama,      // untuk FullCalendar
                'start'        => $patient->tarikh_temu, // untuk FullCalendar
            ];
        });

        return response()->json($formatted);
    }
}
