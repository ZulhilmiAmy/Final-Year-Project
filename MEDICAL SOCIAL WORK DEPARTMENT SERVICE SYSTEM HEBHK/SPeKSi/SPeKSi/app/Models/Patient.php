<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        // referral/basic
        'no_fail',
        'tarikh_rujukan',
        'tarikh_tindakbalas_awal',
        // personal
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
        // medical
        'tarikh_masuk_wad',
        'tarikh_discaj',
        'diagnosa',
        'prognosis',
        'mobiliti',
        // arrays
        'bantuan_praktik',
        'terapi_sokongan',
        'kategori_kes',
        'lain_lain',
        // perujuk
        'nama_perujuk',
        'disiplin',
        'wad_rujuk',
        'diagnosis_rujuk',
        // agency
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
        // appointment
        'pegawai_kes',
        'tarikh_temu',
        'masa_temu',
        'catatan_temu'
    ];

    protected $casts = [
        'bantuan_praktik' => 'array',
        'terapi_sokongan' => 'array',
        'kategori_kes' => 'array',
    ];

}
