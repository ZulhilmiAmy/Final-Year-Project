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

    // Senaraikan warna untuk pegawai (ikut giliran)
    $colors = [
        '#28a745', // hijau
        '#007bff', // biru
        '#fd7e14', // oren
        '#6f42c1', // ungu
        '#e83e8c'  // pink
    ];

    $pegawaiColors = []; // simpan mapping pegawai -> warna
    $colorIndex = 0;

    $formatted = $appointments->map(function ($patient) use (&$pegawaiColors, &$colorIndex, $colors) {
        $pegawai = $patient->pegawai_kes ?? 'Tidak Ditetapkan';

        // assign warna ikut pegawai
        if (!isset($pegawaiColors[$pegawai])) {
            $pegawaiColors[$pegawai] = $colors[$colorIndex % count($colors)];
            $colorIndex++;
        }

        return [
            'id'           => $patient->id,
            'patient_name' => $patient->nama,
            'date'         => $patient->tarikh_temu,
            'title'        => $patient->nama,
            'start'        => $patient->tarikh_temu,
            'pegawai_kes'  => $pegawai,
            'color'        => $pegawaiColors[$pegawai], // ✅ warna unik ikut pegawai
        ];
    });

    return response()->json($formatted);
}

}
