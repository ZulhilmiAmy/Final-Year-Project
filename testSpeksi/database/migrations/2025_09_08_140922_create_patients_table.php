<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();

            // Basic / referral
            $table->string('no_fail')->nullable();
            $table->date('tarikh_rujukan')->nullable();
            $table->date('tarikh_tindakbalas_awal')->nullable();

            // Personal
            $table->string('nama')->nullable();
            $table->string('no_kp')->nullable();
            $table->date('tarikh_lahir')->nullable();
            $table->integer('umur')->nullable();
            $table->string('jantina')->nullable();
            $table->string('agama')->nullable();
            $table->string('agama_lain')->nullable();
            $table->string('bangsa')->nullable();
            $table->string('bangsa_lain')->nullable();
            $table->text('alamat')->nullable();
            $table->string('negeri')->nullable();
            $table->string('bandar')->nullable();
            $table->string('poskod')->nullable();
            $table->string('no_tel')->nullable();

            // Medical
            $table->date('tarikh_masuk_wad')->nullable();
            $table->date('tarikh_discaj')->nullable();
            $table->text('diagnosa')->nullable();
            $table->string('prognosis')->nullable();
            $table->string('mobiliti')->nullable();

            // Tab 2 (arrays)
            $table->json('bantuan_praktik')->nullable();
            $table->json('terapi_sokongan')->nullable();

            // Tab 3 categories
            $table->json('kategori_kes')->nullable();
            $table->string('lain_lain')->nullable();

            // Tab 4 perujuk
            $table->string('nama_perujuk')->nullable();
            $table->string('disiplin')->nullable();
            $table->string('wad_rujuk')->nullable();
            $table->string('diagnosis_rujuk')->nullable();

            // Tab 5 agency
            $table->string('agensi')->nullable();
            $table->string('pembekal')->nullable();
            $table->date('tarikh_laporan')->nullable();
            $table->date('tarikh_dokumen_lengkap')->nullable();
            $table->string('item_dipohon')->nullable();
            $table->date('tarikh_kelulusan')->nullable();
            $table->decimal('tanggungan', 10, 2)->nullable();
            $table->decimal('jumlah_dipohon', 12, 2)->nullable();
            $table->decimal('jumlah_kelulusan', 12, 2)->nullable();
            $table->date('tarikh_tuntut')->nullable();

            // Tab 6 appointment (simple single appointment stored with patient)
            $table->string('pegawai_kes')->nullable();
            $table->date('tarikh_temu')->nullable();
            $table->time('masa_temu')->nullable();
            $table->text('catatan_temu')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('patients');
    }

    public function search(Request $request)
    {
        $ic = $request->query('no_kp');

        $patient = \App\Models\Patient::where('no_kp', $ic)->first();

        if ($patient) {
            return response()->json([
                'exists' => true,
                'data' => [
                    'name' => $patient->nama,
                    'date' => $patient->tarikh_rujukan, // atau field lain yg sesuai
                ]
            ]);
        }

        return response()->json(['exists' => false]);
    }
}
