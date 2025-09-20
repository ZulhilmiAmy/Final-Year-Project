<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf; // alias pakej
use App\Models\Appointment;

class PatientController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'nullable|string|max:255',
            'no_kp' => 'nullable|string|max:20',
            'tarikh_rujukan' => 'nullable|date',
            'tarikh_tindakbalas_awal' => 'nullable|date',
        ]);

        $data = $request->only([
            'no_fail',
            'tarikh_rujukan',
            'tarikh_tindakbalas_awal',
            'nama',
            'no_kp',
            'tarikh_lahir',
            'umur',
            'jantina',
            'agama',
            'agama_lain',
            'bangsa',
            'bangsa_lain',
            'alamat',
            'negeri',
            'bandar',
            'poskod',
            'no_tel',
            'tarikh_masuk_wad',
            'tarikh_discaj',
            'diagnosa',
            'prognosis',
            'mobiliti',
            'nama_perujuk',
            'disiplin',
            'wad_rujuk',
            'diagnosis_rujuk',
            'agensi',
            'pembekal',
            'tarikh_laporan',
            'tarikh_dokumen_lengkap',
            'item_dipohon',
            'tarikh_kelulusan',
            'tanggungan',
            'jumlah_dipohon',
            'jumlah_kelulusan',
            'tarikh_tuntut',
            'pegawai_kes',
            'tarikh_temu',
            'masa_temu',
            'catatan_temu',
            'lain_lain'
        ]);

        if ($request->has('bantuan_praktik')) {
            $data['bantuan_praktik'] = is_array($request->input('bantuan_praktik'))
                ? implode(', ', $request->input('bantuan_praktik'))
                : $request->input('bantuan_praktik');
        }

        if ($request->has('terapi_sokongan')) {
            $data['terapi_sokongan'] = is_array($request->input('terapi_sokongan'))
                ? implode(', ', $request->input('terapi_sokongan'))
                : $request->input('terapi_sokongan');
        }

        if ($request->has('kategori_kes')) {
            $data['kategori_kes'] = is_array($request->input('kategori_kes'))
                ? implode(', ', $request->input('kategori_kes'))
                : $request->input('kategori_kes');
        }

        $patient = Patient::create($data);

        // 🔹 Selepas simpan → balik ke Home Page + flash message
        return redirect()->route('home')
            ->with('success', 'Permohonan berjaya dihantar! Surat temujanji telah dijana.')
            ->with('pdf_url', route('patients.letter', $patient->id));
    }


    public function search(Request $request)
    {
        $no_kp = $request->get('no_kp');

        $patient = Patient::where('no_kp', $no_kp)->first();

        if (!$patient) {
            return response()->json([
                'exists' => false,
                'debug' => [
                    'searched_no_kp' => $no_kp,
                    'all_patients' => Patient::pluck('no_kp')
                ]
            ]);
        }

        return response()->json([
            'exists' => true,
            'data' => [
                'name' => $patient->nama,
                'no_kp' => $patient->no_kp,
                'date' => $patient->tarikh_rujukan
                    ? Carbon::parse($patient->tarikh_rujukan)->format('d-m-Y')
                    : null,
            ]
        ]);
    }

    public function letter(Patient $patient)
    {
        $tarikhTemu = $patient->tarikh_temu ? Carbon::parse($patient->tarikh_temu) : null;
        $hariTemu = $tarikhTemu ? $tarikhTemu->locale('ms')->isoFormat('dddd') : null;
        $tarikhTemuFormatted = $tarikhTemu ? $tarikhTemu->format('d-m-Y') : '-';

        $alamatFormatted = nl2br(e($patient->alamat ?? ''));

        $pdf = Pdf::loadView('letters.appointment', compact(
            'patient',
            'tarikhTemu',
            'tarikhTemuFormatted',
            'hariTemu',
            'alamatFormatted'
        ));

        return $pdf->stream("Surat_Temujanji_{$patient->no_kp}.pdf");
    }

    public function history(Request $request)
    {
        $no_kp = $request->query('no_kp');

        if (!$no_kp) {
            $patients = collect();
            return view('patients.history', compact('patients', 'no_kp'));
        }

        $patients = Patient::where('no_kp', $no_kp)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('patients.history', compact('patients', 'no_kp'));
    }

    public function create(Request $request)
    {
        $kp = $request->query('kp');

        // Ambil semua temujanji daripada patients
        $appointments = Patient::whereNotNull('tarikh_temu')
            ->whereNotNull('masa_temu')
            ->get();

        // Format ikut FullCalendar
        $events = $appointments->map(function ($a) {
            return [
                'title' => strtoupper($a->nama ?? 'PEMOHON') . ' - ' . ($a->catatan_temu ?? ''),
                'start' => $a->tarikh_temu . 'T' . $a->masa_temu,
                'allDay' => false,
                'extendedProps' => [
                    'pegawai' => $a->pegawai_kes ?? '-',
                    'no_kp' => $a->no_kp ?? '-',
                ],
            ];
        });

        // 🔹 Bezakan ikut role
// 🔹 Bezakan ikut role
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user && strtolower($user->role) === 'admin') {
            // Kalau admin → ambil dari folder admin
            return view('admin.createAdmin', compact('kp', 'events'));
        } else {
            // Kalau user biasa → ambil dari folder patients
            return view('patients.create', compact('kp', 'events'));
        }
    }



    public function letterByNoKp($no_kp)
    {
        $patient = Patient::where('no_kp', $no_kp)->first();

        if (!$patient) {
            return redirect()->route('home')->with('error', "Tiada rekod pesakit untuk IC: {$no_kp}");
        }

        $tarikhTemu = $patient->tarikh_temu ? Carbon::parse($patient->tarikh_temu) : null;
        $hariTemu = $tarikhTemu ? $tarikhTemu->locale('ms')->isoFormat('dddd') : null;
        $tarikhTemuFormatted = $tarikhTemu ? $tarikhTemu->format('d-m-Y') : '-';

        $alamatFormatted = nl2br(e($patient->alamat ?? ''));

        $pdf = Pdf::loadView('letters.appointment', compact(
            'patient',
            'tarikhTemu',
            'tarikhTemuFormatted',
            'hariTemu',
            'alamatFormatted'
        ));

        return $pdf->stream("Surat_Temujanji_{$patient->no_kp}.pdf");
    }
    public function edit($id)
    {
        $patient = Patient::findOrFail($id);

        // ambil semua temujanji untuk calendar
        $appointments = Patient::whereNotNull('tarikh_temu')
            ->whereNotNull('masa_temu')
            ->get();

        $events = $appointments->map(function ($a) {
            return [
                'title' => strtoupper($a->nama ?? 'PEMOHON') . ' - ' . ($a->catatan_temu ?? ''),
                'start' => $a->tarikh_temu . 'T' . $a->masa_temu,
                'allDay' => false,
                'extendedProps' => [
                    'pegawai' => $a->pegawai_kes ?? '-',
                    'no_kp' => $a->no_kp ?? '-',
                ],
            ];
        });

        return view('patients.edit', compact('patient', 'events'));
    }


    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);
        $patient->update($request->all());

        return redirect()->route('patients.show', $patient->id)
            ->with('success', 'Maklumat pesakit berjaya dikemaskini!');
    }

    public function show(\App\Models\Patient $patient)
    {
        // jika ada kolum simpanan array dalam JSON/text, boleh decode di sini jika mahu
        // contoh: $bantuan = json_decode($patient->bantuan_praktik, true) ?: [];

        return view('patients.show', compact('patient'));
    }
}
