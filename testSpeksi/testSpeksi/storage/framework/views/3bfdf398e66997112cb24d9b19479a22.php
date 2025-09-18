
<?php
    use App\Models\Patient as _P;

    // Untuk calendar events kalau controller tak pass $events
    $appointments = _P::whereNotNull('tarikh_temu')->whereNotNull('masa_temu')->get();
    $events = $appointments->map(function ($a) {
        return [
            'title' => strtoupper($a->nama ?? 'PEMOHON') . ' - ' . ($a->catatan_temu ?? ''),
            'start' => ($a->tarikh_temu ? $a->tarikh_temu : '') . 'T' . ($a->masa_temu ? $a->masa_temu : ''),
            'allDay' => false,
            'extendedProps' => [
                'pegawai' => $a->pegawai_kes ?? '-',
                'no_kp' => $a->no_kp ?? '-'
            ]
        ];
    });
?>

<!DOCTYPE html>
<html lang="ms">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pesakit - <?php echo e($patient->nama); ?></title>
    <!-- Styles (copy dari create.blade.php) -->
    <style>
        /* (sama seperti style awak asal - saya ringkaskan sedikit di sini, 
           tetapi ianya sama dengan create.blade.php yang awak guna) */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: linear-gradient(to bottom right, #e6f2f8, #f0f6fa);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale
        }

        .header {
            background-color: #111;
            color: white;
            text-align: center
        }

        .header img {
            width: 100%;
            height: auto;
            display: block;
            max-height: 140px;
            object-fit: cover
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            background-color: white;
            padding: 10px 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, .1)
        }

        .container h1 {
            margin: 5px 0 10px 0;
            color: #333;
            font-size: x-large
        }

        .compartment {
            display: none;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            background-color: #f9f9f9;
            margin-bottom: 20px
        }

        .compartment.active {
            display: block
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            text-transform: uppercase
        }

        textarea {
            resize: vertical
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px 15px -10px
        }

        .form-col {
            flex: 1;
            padding: 0 10px;
            min-width: 200px
        }

        .form-section {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px dashed #ddd
        }

        .form-section-title {
            font-size: 16px;
            color: #204d84;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 2px solid #204d84;
            font-weight: bold
        }

        .checkbox-group {
            background: #f8f8f8;
            padding: 12px;
            border-radius: 5px;
            border: 1px solid #eee;
            margin-bottom: 15px
        }

        .checkbox-group label {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            font-weight: normal;
            text-transform: uppercase
        }

        .tab-container {
            display: flex;
            flex-wrap: wrap;
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px
        }

        .tab {
            padding: 10px 15px;
            background: #f1f1f1;
            border: 1px solid #ddd;
            border-bottom: none;
            border-radius: 5px 5px 0 0;
            margin-right: 5px;
            cursor: pointer
        }

        .tab.active {
            background: #f9f9f9;
            font-weight: bold;
            color: #2196F3
        }

        .button-group {
            text-align: right;
            margin-top: 20px
        }

        button {
            padding: 10px 20px;
            margin-left: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold
        }

        .save-btn {
            background: #4CAF50;
            color: #fff
        }

        .next-btn {
            background: #2196F3;
            color: #fff
        }

        .back-btn {
            background: #f44336;
            color: #fff
        }

        #notif {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #4CAF50;
            color: #fff;
            padding: 12px 20px;
            border-radius: 6px;
            display: none;
            z-index: 1000
        }

        @media (max-width:768px) {
            .form-col {
                flex: 100%
            }
        }
    </style>

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
</head>

<body>
    <div class="header">
        <img src="https://raw.githubusercontent.com/ZulhilmiAmy/Final-Year-Project/main/Image%20banner/Banner-SPeKSi.png"
            alt="Banner">
    </div>

    <div class="container">
        <h1>Edit Maklumat Pesakit</h1>

        <?php if(session('success')): ?>
            <div style="background:#4caf50;color:#fff;padding:8px;border-radius:6px;margin-bottom:10px;">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        
        <form id="patientForm" method="POST" action="<?php echo e(route('patients.update', $patient->id)); ?>"
            onsubmit="return validateFinal()">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="tab-container">
                <div class="tab active" data-tab="1">1. Maklumat Peribadi</div>
                <div class="tab" data-tab="2">2. Tujuan Rujukan</div>
                <div class="tab" data-tab="3">3. Kategori Kes</div>
                <div class="tab" data-tab="4">4. Maklumat Perujuk</div>
                <div class="tab" data-tab="5">5. Rujukan Agensi</div>
                <div class="tab" data-tab="6">6. Tarikh Temu Janji</div>
            </div>

            <!-- TAB 1 -->
            <div class="compartment active" id="compartment1">
                <h3>Maklumat Peribadi</h3>

                <div class="form-section">
                    <div class="form-section-title">Maklumat Rujukan</div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Tarikh Rujukan Diterima</label>
                                <input id="tarikhrujukan" type="date" name="tarikh_rujukan"
                                    value="<?php echo e(old('tarikh_rujukan', $patient->tarikh_rujukan)); ?>">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Tarikh Tindakbalas Awal</label>
                                <input id="tindakawal" type="date" name="tarikh_tindakbalas_awal"
                                    value="<?php echo e(old('tarikh_tindakbalas_awal', $patient->tarikh_tindakbalas_awal)); ?>">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>No. Fail JKSP</label>
                                <input id="nofile" name="no_fail" value="<?php echo e(old('no_fail', $patient->no_fail)); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">Maklumat Asas Pesakit</div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Nama</label>
                                <input id="nama" name="nama" value="<?php echo e(old('nama', $patient->nama)); ?>">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>No. KP</label>
                                <input id="nokp" name="no_kp" maxlength="12" value="<?php echo e(old('no_kp', $patient->no_kp)); ?>"
                                    oninput="autoTarikhUmur()">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Tarikh Lahir</label>
                                <input id="tarikhlahir" type="date" name="tarikh_lahir"
                                    value="<?php echo e(old('tarikh_lahir', $patient->tarikh_lahir)); ?>">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Umur</label>
                                <input id="umur" name="umur" value="<?php echo e(old('umur', $patient->umur)); ?>" readonly>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Jantina</label>
                                <input id="jantina" name="jantina" value="<?php echo e(old('jantina', $patient->jantina)); ?>"
                                    readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Agama</label>
                                <select id="agama" name="agama" onchange="toggleAgamaLain()">
                                    <option value="" disabled <?php echo e(old('agama', $patient->agama) ? '' : 'selected'); ?>>--
                                        Sila Pilih --</option>
                                    <option value="Islam" <?php echo e((old('agama', $patient->agama) == 'Islam') ? 'selected' : ''); ?>>Islam</option>
                                    <option value="Buddha" <?php echo e((old('agama', $patient->agama) == 'Buddha') ? 'selected' : ''); ?>>Buddha</option>
                                    <option value="Hindu" <?php echo e((old('agama', $patient->agama) == 'Hindu') ? 'selected' : ''); ?>>Hindu</option>
                                    <option value="Kristian" <?php echo e((old('agama', $patient->agama) == 'Kristian') ? 'selected' : ''); ?>>Kristian</option>
                                    <option value="Lain" <?php echo e((old('agama', $patient->agama) == 'Lain') ? 'selected' : ''); ?>>Lain-lain</option>
                                </select>
                                <input id="agamaLain" name="agama_lain" placeholder="Sila nyatakan"
                                    style="display:<?php echo e((old('agama_lain', $patient->agama_lain) ? 'block' : (old('agama', $patient->agama) == 'Lain' ? 'block' : 'none'))); ?>; margin-top:5px;"
                                    value="<?php echo e(old('agama_lain', $patient->agama_lain)); ?>">
                            </div>
                        </div>

                        <div class="form-col">
                            <div class="form-group">
                                <label>Bangsa</label>
                                <select id="bangsa" name="bangsa" onchange="toggleBangsaLain()">
                                    <option value="" disabled <?php echo e(old('bangsa', $patient->bangsa) ? '' : 'selected'); ?>>--
                                        Sila Pilih --</option>
                                    <option value="Melayu" <?php echo e((old('bangsa', $patient->bangsa) == 'Melayu') ? 'selected' : ''); ?>>Melayu</option>
                                    <option value="Cina" <?php echo e((old('bangsa', $patient->bangsa) == 'Cina') ? 'selected' : ''); ?>>Cina</option>
                                    <option value="India" <?php echo e((old('bangsa', $patient->bangsa) == 'India') ? 'selected' : ''); ?>>India</option>
                                    <option value="Bumiputera Sabah" <?php echo e((old('bangsa', $patient->bangsa) == 'Bumiputera Sabah') ? 'selected' : ''); ?>>Bumiputera Sabah</option>
                                    <option value="Bumiputera Sarawak" <?php echo e((old('bangsa', $patient->bangsa) == 'Bumiputera Sarawak') ? 'selected' : ''); ?>>Bumiputera Sarawak</option>
                                    <option value="Lain" <?php echo e((old('bangsa', $patient->bangsa) == 'Lain') ? 'selected' : ''); ?>>Lain-lain</option>
                                </select>
                                <input id="bangsaLain" name="bangsa_lain" placeholder="Sila nyatakan"
                                    style="display:<?php echo e((old('bangsa_lain', $patient->bangsa_lain) ? 'block' : (old('bangsa', $patient->bangsa) == 'Lain' ? 'block' : 'none'))); ?>; margin-top:5px;"
                                    value="<?php echo e(old('bangsa_lain', $patient->bangsa_lain)); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea id="alamat" name="alamat" rows="2"><?php echo e(old('alamat', $patient->alamat)); ?></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Negeri</label>
                                <select id="negeri" name="negeri">
                                    <option value="" disabled <?php echo e(old('negeri', $patient->negeri) ? '' : 'selected'); ?>>--
                                        Sila Pilih --</option>
                                    <?php $__currentLoopData = ['Johor', 'Kedah', 'Kelantan', 'Melaka', 'Negeri Sembilan', 'Pahang', 'Perak', 'Perlis', 'Pulau Pinang', 'Sabah', 'Sarawak', 'Selangor', 'Terengganu', 'W.P. Kuala Lumpur', 'W.P. Labuan', 'W.P. Putrajaya']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($n); ?>" <?php echo e((old('negeri', $patient->negeri) == $n) ? 'selected' : ''); ?>><?php echo e($n); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-col">
                            <div class="form-group">
                                <label>Bandar</label>
                                <input id="bandar" name="bandar" value="<?php echo e(old('bandar', $patient->bandar)); ?>">
                            </div>
                        </div>

                        <div class="form-col">
                            <div class="form-group">
                                <label>Poskod</label>
                                <input id="poskod" name="poskod" maxlength="5"
                                    value="<?php echo e(old('poskod', $patient->poskod)); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>No. Tel.</label>
                                <input id="notel" name="no_tel" value="<?php echo e(old('no_tel', $patient->no_tel)); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">Maklumat Perubatan</div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Tarikh Masuk Wad</label>
                                <input id="tarikhmasuk" type="date" name="tarikh_masuk_wad"
                                    value="<?php echo e(old('tarikh_masuk_wad', $patient->tarikh_masuk_wad)); ?>">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Tarikh Dijangka Discaj</label>
                                <input id="tarikhdiscaj" type="date" name="tarikh_discaj"
                                    value="<?php echo e(old('tarikh_discaj', $patient->tarikh_discaj)); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Diagnosa</label>
                        <textarea id="diagnosa" name="diagnosa"
                            rows="2"><?php echo e(old('diagnosa', $patient->diagnosa)); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Prognosis</label>
                        <select id="prognosis" name="prognosis">
                            <option value="" disabled <?php echo e(old('prognosis', $patient->prognosis) ? '' : 'selected'); ?>>
                                --Sila Pilih--</option>
                            <option value="Baik (Good)" <?php echo e((old('prognosis', $patient->prognosis) == 'Baik (Good)') ? 'selected' : ''); ?>>Baik (Good)</option>
                            <option value="Sederhana (Fair)" <?php echo e((old('prognosis', $patient->prognosis) == 'Sederhana (Fair)') ? 'selected' : ''); ?>>Sederhana (Fair)</option>
                            <option value="Tidak Baik (Poor)" <?php echo e((old('prognosis', $patient->prognosis) == 'Tidak Baik (Poor)') ? 'selected' : ''); ?>>Tidak Baik (Poor)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Mobiliti Pesakit</label>
                        <select id="mobiliti" name="mobiliti">
                            <option value="" disabled <?php echo e(old('mobiliti', $patient->mobiliti) ? '' : 'selected'); ?>>--
                                Sila Pilih --</option>
                            <option value="Berjalan" <?php echo e((old('mobiliti', $patient->mobiliti) == 'Berjalan') ? 'selected' : ''); ?>>Berjalan</option>
                            <option value="Kerusi Roda" <?php echo e((old('mobiliti', $patient->mobiliti) == 'Kerusi Roda') ? 'selected' : ''); ?>>Kerusi Roda</option>
                            <option value="Crutches" <?php echo e((old('mobiliti', $patient->mobiliti) == 'Crutches') ? 'selected' : ''); ?>>Crutches</option>
                            <option value="Walking Frame" <?php echo e((old('mobiliti', $patient->mobiliti) == 'Walking Frame') ? 'selected' : ''); ?>>Walking Frame</option>
                            <option value="Bedridden" <?php echo e((old('mobiliti', $patient->mobiliti) == 'Bedridden') ? 'selected' : ''); ?>>Bedridden</option>
                        </select>
                    </div>
                </div>

                <div class="button-group">
                    <button type="button" class="next-btn" onclick="next(1)">Seterusnya</button>
                </div>
            </div> <!-- end TAB1 -->

            
            <div class="compartment" id="compartment2">
                <h3>Tujuan Rujukan</h3>

                <?php
                    $bantuan = old('bantuan_praktik', $patient->bantuan_praktik ?? []);
                    if (!is_array($bantuan) && $bantuan !== null) {
                        $tmp = json_decode($bantuan, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($tmp)) {
                            $bantuan = $tmp;
                        } else {
                            $bantuan = array_filter(array_map('trim', explode(',', (string) $bantuan)));
                        }
                    }
                    $terapi = old('terapi_sokongan', $patient->terapi_sokongan ?? []);
                    if (!is_array($terapi) && $terapi !== null) {
                        $tmp = json_decode($terapi, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($tmp)) {
                            $terapi = $tmp;
                        } else {
                            $terapi = array_filter(array_map('trim', explode(',', (string) $terapi)));
                        }
                    }
                ?>

                <div class="form-section">
                    <div class="form-section-title">BANTUAN PRAKTIK</div>
                    <div class="checkbox-group">
                        <label><input type="checkbox" class="bantuanpraktik" name="bantuan_praktik[]"
                                value="Pembiayaan Peralatan Perubatan" <?php echo e(in_array('Pembiayaan Peralatan Perubatan', (array) $bantuan) ? 'checked' : ''); ?>> Pembiayaan Peralatan Perubatan</label>
                        <label><input type="checkbox" class="bantuanpraktik" name="bantuan_praktik[]"
                                value="Bantuan Pembiayaan Rawatan" <?php echo e(in_array('Bantuan Pembiayaan Rawatan', (array) $bantuan) ? 'checked' : ''); ?>> Bantuan Pembiayaan Rawatan</label>
                        <label><input type="checkbox" class="bantuanpraktik" name="bantuan_praktik[]"
                                value="Bantuan Pembiayaan Ubat-Ubatan" <?php echo e(in_array('Bantuan Pembiayaan Ubat-Ubatan', (array) $bantuan) ? 'checked' : ''); ?>> Bantuan Pembiayaan Ubat-Ubatan</label>
                        <label><input type="checkbox" class="bantuanpraktik" name="bantuan_praktik[]" value="Bantuan Am"
                                <?php echo e(in_array('Bantuan Am', (array) $bantuan) ? 'checked' : ''); ?>> Bantuan Am</label>
                        <label><input type="checkbox" class="bantuanpraktik" name="bantuan_praktik[]"
                                value="Penempatan Institusi" <?php echo e(in_array('Penempatan Institusi', (array) $bantuan) ? 'checked' : ''); ?>> Penempatan Institusi</label>
                        <label><input type="checkbox" class="bantuanpraktik" name="bantuan_praktik[]"
                                value="Mengesan Waris" <?php echo e(in_array('Mengesan Waris', (array) $bantuan) ? 'checked' : ''); ?>> Mengesan Waris</label>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">TERAPI SOKONGAN</div>
                    <div class="checkbox-group">
                        <label><input type="checkbox" class="terapisokongan" name="terapi_sokongan[]"
                                value="Khidmat Perundingan" <?php echo e(in_array('Khidmat Perundingan', (array) $terapi) ? 'checked' : ''); ?>> Khidmat Perundingan</label>
                        <label><input type="checkbox" class="terapisokongan" name="terapi_sokongan[]"
                                value="Sokongan Emosi" <?php echo e(in_array('Sokongan Emosi', (array) $terapi) ? 'checked' : ''); ?>> Sokongan Emosi</label>
                        <label><input type="checkbox" class="terapisokongan" name="terapi_sokongan[]"
                                value="Intervensi Krisis" <?php echo e(in_array('Intervensi Krisis', (array) $terapi) ? 'checked' : ''); ?>> Intervensi Krisis</label>
                    </div>
                </div>

                <div class="button-group">
                    <button type="button" class="back-btn" onclick="back(2)">Kembali</button>
                    <button type="button" class="next-btn" onclick="next(2)">Seterusnya</button>
                </div>
            </div>

            
            <div class="compartment" id="compartment3">
                <h3>Kategori Kes</h3>

                <?php
                    $kategori = old('kategori_kes', $patient->kategori_kes ?? []);
                    if (!is_array($kategori) && $kategori !== null) {
                        $tmp = json_decode($kategori, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($tmp)) {
                            $kategori = $tmp;
                        } else {
                            $kategori = array_filter(array_map('trim', explode(',', (string) $kategori)));
                        }
                    }
                ?>

                <div class="checkbox-group">
                    <label><input type="checkbox" name="kategori_kes[]" value="Penyakit Kronik" <?php echo e(in_array('Penyakit Kronik', (array) $kategori) ? 'checked' : ''); ?>> Penyakit Kronik</label>
                    <label><input type="checkbox" name="kategori_kes[]" value="Penderaan Kanak-kanak" <?php echo e(in_array('Penderaan Kanak-kanak', (array) $kategori) ? 'checked' : ''); ?>> Penderaan
                        Kanak-kanak</label>
                    <label><input type="checkbox" name="kategori_kes[]" value="Keganasan Rumah Tangga" <?php echo e(in_array('Keganasan Rumah Tangga', (array) $kategori) ? 'checked' : ''); ?>> Keganasan Rumah
                        Tangga</label>
                    <label><input type="checkbox" name="kategori_kes[]" value="Ibu Tanpa Nikah" <?php echo e(in_array('Ibu Tanpa Nikah', (array) $kategori) ? 'checked' : ''); ?>> Ibu Tanpa Nikah</label>
                    <label><input type="checkbox" name="kategori_kes[]" value="Keganasan Seksual" <?php echo e(in_array('Keganasan Seksual', (array) $kategori) ? 'checked' : ''); ?>> Keganasan Seksual</label>
                    <label><input type="checkbox" name="kategori_kes[]" value="Pesakit Terdampar" <?php echo e(in_array('Pesakit Terdampar', (array) $kategori) ? 'checked' : ''); ?>> Pesakit Terdampar</label>
                    <label><input type="checkbox" name="kategori_kes[]" value="Masalah Tingkah Laku" <?php echo e(in_array('Masalah Tingkah Laku', (array) $kategori) ? 'checked' : ''); ?>> Masalah Tingkah
                        Laku</label>
                    <label><input type="checkbox" name="kategori_kes[]" value="HIV POSITIF / AIDS" <?php echo e(in_array('HIV POSITIF / AIDS', (array) $kategori) ? 'checked' : ''); ?>> HIV POSITIF / AIDS</label>
                    <label><input type="checkbox" name="kategori_kes[]" value="Dadah / Alkohol" <?php echo e(in_array('Dadah / Alkohol', (array) $kategori) ? 'checked' : ''); ?>> Dadah / Alkohol</label>
                    <label><input type="checkbox" name="kategori_kes[]" value="Cubaan Bunuh Diri" <?php echo e(in_array('Cubaan Bunuh Diri', (array) $kategori) ? 'checked' : ''); ?>> Cubaan Bunuh Diri</label>
                    <label><input type="checkbox" name="kategori_kes[]" value="OKU" <?php echo e(in_array('OKU', (array) $kategori) ? 'checked' : ''); ?>> OKU</label>
                </div>

                <div class="form-group">
                    <label><input type="checkbox" id="lainCheck" onclick="toggleLainTextbox()" <?php echo e($patient->lain_lain || old('lain_lain') ? 'checked' : ''); ?>> Lain-lain</label>
                    <input id="lain" name="lain_lain" type="text" value="<?php echo e(old('lain_lain', $patient->lain_lain)); ?>"
                        style="display:<?php echo e(old('lain_lain', $patient->lain_lain) ? 'block' : 'none'); ?>; margin-top:5px;">
                </div>

                <div class="button-group">
                    <button type="button" class="back-btn" onclick="back(3)">Kembali</button>
                    <button type="button" class="next-btn" onclick="next(3)">Seterusnya</button>
                </div>
            </div>

            
            <div class="compartment" id="compartment4">
                <h3>Maklumat Perujuk</h3>

                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label>Nama Perujuk</label>
                            <input id="namaperujuk" name="nama_perujuk"
                                value="<?php echo e(old('nama_perujuk', $patient->nama_perujuk)); ?>">
                        </div>
                    </div>

                    <div class="form-col">
                        <div class="form-group">
                            <label>Nama Disiplin</label>
                            <select id="disiplin" name="disiplin">
                                <option value="" disabled <?php echo e(old('disiplin', $patient->disiplin) ? '' : 'selected'); ?>>--
                                    Pilih Disiplin --</option>
                                <?php $__currentLoopData = ['MED', 'SURG', 'O&G', 'ORTHO', 'PAEDS', 'OFTAL', 'ENT', 'A&E', 'ONCHO', 'PSY', 'REHAB', 'NEFRO', 'URO', 'DERMA', 'NEURO', 'NEUROS', 'HAEMATO', 'CARDIO', 'ETC']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($d); ?>" <?php echo e((old('disiplin', $patient->disiplin) == $d) ? 'selected' : ''); ?>><?php echo e($d); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label>Punca Rujukan</label>
                            <select id="wadrujuk" name="wad_rujuk">
                                <option value="" disabled <?php echo e(old('wad_rujuk', $patient->wad_rujuk) ? '' : 'selected'); ?>>
                                    -- Pilih Lokasi --</option>
                                <?php $__currentLoopData = ['Wad', 'KLINIK', 'JPL', 'ED']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($w); ?>" <?php echo e((old('wad_rujuk', $patient->wad_rujuk) == $w) ? 'selected' : ''); ?>><?php echo e($w); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-col">
                        <div class="form-group">
                            <label>Diagnosis</label>
                            <input id="diagnosisrujuk" name="diagnosis_rujuk"
                                value="<?php echo e(old('diagnosis_rujuk', $patient->diagnosis_rujuk)); ?>">
                        </div>
                    </div>
                </div>

                <div class="button-group">
                    <button type="button" class="back-btn" onclick="back(4)">Kembali</button>
                    <button type="button" class="next-btn" onclick="next(4)">Seterusnya</button>
                </div>
            </div>

            
            <div class="compartment" id="compartment5">
                <h3>Rujukan Agensi</h3>

                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label>Nama Agensi</label>
                            <input id="agensi" name="agensi" value="<?php echo e(old('agensi', $patient->agensi)); ?>">
                        </div>
                    </div>

                    <div class="form-col">
                        <div class="form-group">
                            <label>Nama Pembekal</label>
                            <input id="pembekal" name="pembekal" value="<?php echo e(old('pembekal', $patient->pembekal)); ?>">
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label>Tarikh Laporan Dihantar</label>
                            <input type="date" name="tarikh_laporan"
                                value="<?php echo e(old('tarikh_laporan', $patient->tarikh_laporan)); ?>">
                        </div>
                    </div>

                    <div class="form-col">
                        <div class="form-group">
                            <label>Tarikh Dokumen Lengkap Diterima</label>
                            <input type="date" name="tarikh_dokumen_lengkap"
                                value="<?php echo e(old('tarikh_dokumen_lengkap', $patient->tarikh_dokumen_lengkap)); ?>">
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label>Item Dipohon</label>
                            <input name="item_dipohon" value="<?php echo e(old('item_dipohon', $patient->item_dipohon)); ?>">
                        </div>
                    </div>

                    <div class="form-col">
                        <div class="form-group">
                            <label>Tarikh Kelulusan</label>
                            <input type="date" name="tarikh_kelulusan"
                                value="<?php echo e(old('tarikh_kelulusan', $patient->tarikh_kelulusan)); ?>">
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label>Tanggungan Pesakit (RM)</label>
                            <input type="number" step="0.01" name="tanggungan"
                                value="<?php echo e(old('tanggungan', $patient->tanggungan)); ?>">
                        </div>
                    </div>

                    <div class="form-col">
                        <div class="form-group">
                            <label>Jumlah Dipohon (RM)</label>
                            <input type="number" step="0.01" name="jumlah_dipohon"
                                value="<?php echo e(old('jumlah_dipohon', $patient->jumlah_dipohon)); ?>">
                        </div>
                    </div>

                    <div class="form-col">
                        <div class="form-group">
                            <label>Jumlah Kelulusan (RM)</label>
                            <input type="number" step="0.01" name="jumlah_kelulusan"
                                value="<?php echo e(old('jumlah_kelulusan', $patient->jumlah_kelulusan)); ?>">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Tarikh Tuntut</label>
                    <input type="date" name="tarikh_tuntut" value="<?php echo e(old('tarikh_tuntut', $patient->tarikh_tuntut)); ?>">
                </div>

                <div class="button-group">
                    <button type="button" class="back-btn" onclick="back(5)">Kembali</button>
                    <button type="button" class="next-btn" onclick="next(5)">Seterusnya</button>
                </div>
            </div>

            
            <div class="compartment" id="compartment6">
                <h3>Tarikh Temu Janji</h3>

                <div id="calendar-container">
                    <div id="calendarTab6"></div>
                </div>

                <div class="form-group">
                    <label>Pegawai Kes</label>
                    <input id="pegawaikes" name="pegawai_kes" value="<?php echo e(old('pegawai_kes', $patient->pegawai_kes)); ?>">
                </div>

                <div class="form-group">
                    <label>Tarikh Temu Janji</label>
                    <input id="tarikhtemujanji" type="date" name="tarikh_temu"
                        value="<?php echo e(old('tarikh_temu', $patient->tarikh_temu)); ?>">
                </div>

                <div class="form-group">
                    <label>Masa Temu Janji</label>
                    <input id="masatemujanji" type="time" name="masa_temu"
                        value="<?php echo e(old('masa_temu', $patient->masa_temu)); ?>">
                </div>

                <div class="form-group">
                    <label>Catatan Temu Janji</label>
                    <textarea id="catatantemujanji" name="catatan_temu"
                        rows="3"><?php echo e(old('catatan_temu', $patient->catatan_temu)); ?></textarea>
                </div>

                <div class="button-group">
                    <button type="button" class="back-btn" onclick="back(6)">Kembali</button>
                    <button type="submit" class="save-btn">Kemaskini</button>
                </div>
            </div>

        </form> <!-- end form -->
    </div> <!-- end container -->

    <script>
        // Sama JavaScript seperti create.blade, dengan beberapa id yang sama supaya fungsi bekerja.
        let data = {};
        let calendar;

        document.addEventListener('DOMContentLoaded', function () {
            // FullCalendar
            const calendarEl = document.getElementById('calendarTab6');
            if (calendarEl) {
                calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    height: 'auto',
                    locale: 'ms',
                    headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,timeGridDay' },
                    buttonText: { today: 'Hari Ini', month: 'Bulan', week: 'Minggu', day: 'Hari' },
                    events: <?php echo json_encode($events, 15, 512) ?>,
                    eventClick: function (info) {
                        alert(
                            "Pemohon: " + info.event.title +
                            "\nPegawai Kes: " + info.event.extendedProps.pegawai +
                            "\nNo. KP: " + info.event.extendedProps.no_kp +
                            "\nMasa: " + info.event.start.toLocaleString('ms-MY')
                        );
                    }
                });
                calendar.render();
            }

            // Input handlers (error removal)
            document.querySelectorAll("input, textarea, select").forEach(el => {
                el.addEventListener("input", function () {
                    if (this.tagName !== "SELECT") {
                        this.classList.remove("error");
                        let errorMsg = this.parentNode.querySelector(".error-msg");
                        if (errorMsg) errorMsg.remove();
                    }
                });
                el.addEventListener("change", function () {
                    if (this.tagName === "SELECT" || this.type === "date" || this.type === "time") {
                        if (this.value && this.value !== "") {
                            this.classList.remove("error");
                            let errorMsg = this.parentNode.querySelector(".error-msg");
                            if (errorMsg) errorMsg.remove();
                        }
                    }
                });
            });

            // autoTarikhUmur run if IC already present
            const icInput = document.getElementById("nokp");
            if (icInput && icInput.value.trim() !== "") {
                autoTarikhUmur();
            }
        });

        function toggleLainTextbox() {
            const lainCheckbox = document.getElementById('lainCheck');
            const lainTextbox = document.getElementById('lain');
            if (!lainTextbox) return;
            lainTextbox.style.display = lainCheckbox && lainCheckbox.checked ? 'block' : 'none';
        }

        function switchTab(num) {
            document.querySelectorAll('.compartment').forEach(c => c.classList.remove('active'));
            const target = document.querySelector(`#compartment${num}`);
            if (target) target.classList.add('active');
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            const tab = document.querySelector(`.tab[data-tab="${num}"]`);
            if (tab) tab.classList.add('active');
            if (num === 6 && calendar) {
                setTimeout(() => calendar.updateSize(), 200);
            }
        }

        function next(num) {
            if (!validateTab(num)) return;
            document.querySelector(`.tab[data-tab="${num + 1}"]`)?.classList.remove('disabled');
            switchTab(num + 1);
        }

        function back(num) {
            switchTab(num - 1);
        }

        function validateTab(num) {
            let valid = true;
            let inputs = document.querySelectorAll(`#compartment${num} input, #compartment${num} textarea, #compartment${num} select`);

            // Tab 2 special checks (ensure at least one checkbox per group) - same logic as create
            if (num === 2) {
                const bantuanGroup = document.querySelectorAll('#compartment2 .bantuanpraktik');
                const terapiGroup = document.querySelectorAll('#compartment2 .terapisokongan');
                let bantuanChecked = [...bantuanGroup].some(cb => cb.checked);
                let terapiChecked = [...terapiGroup].some(cb => cb.checked);
                if (!bantuanChecked) {
                    valid = false;
                    bantuanGroup[0]?.closest('.checkbox-group')?.style.setProperty("border", "2px solid red", "important");
                } else { bantuanGroup[0]?.closest('.checkbox-group')?.style.removeProperty("border"); }
                if (!terapiChecked) {
                    valid = false;
                    terapiGroup[0]?.closest('.checkbox-group')?.style.setProperty("border", "2px solid red", "important");
                } else { terapiGroup[0]?.closest('.checkbox-group')?.style.removeProperty("border"); }
            }

            // Tab 3 require at least one category
            if (num === 3) {
                const allCheckboxes = document.querySelectorAll('#compartment3 input[type="checkbox"]');
                let anyChecked = [...allCheckboxes].some(cb => cb.checked);
                if (!anyChecked) {
                    valid = false;
                    document.querySelectorAll('#compartment3 .checkbox-group').forEach(g => g.style.setProperty("border", "2px solid red", "important"));
                } else {
                    document.querySelectorAll('#compartment3 .checkbox-group').forEach(g => g.style.removeProperty("border"));
                }
                // if lain checked require text
                const lainCheck = document.getElementById('lainCheck');
                const lainInput = document.getElementById('lain');
                if (lainCheck && lainCheck.checked && lainInput && !lainInput.value.trim()) {
                    valid = false;
                    lainInput.classList.add('error');
                }
            }

            // basic required check (as in create)
            inputs.forEach(input => {
                if (!input.hasAttribute('name')) return;
                if (input.offsetParent === null) return;
                if (input.type === 'checkbox') return;
                if (!input.value.trim()) {
                    valid = false;
                    input.classList.add('error');
                    // add error-msg if not exists
                    let errorMsg = input.parentNode.querySelector('.error-msg');
                    if (!errorMsg) {
                        errorMsg = document.createElement('div');
                        errorMsg.className = 'error-msg';
                        errorMsg.style.color = 'red';
                        errorMsg.style.fontSize = '12px';
                        errorMsg.style.marginTop = '3px';
                        input.parentNode.appendChild(errorMsg);
                    }
                    errorMsg.innerText = 'Medan ini wajib diisi.';
                } else {
                    input.classList.remove('error');
                    let errorMsg = input.parentNode.querySelector('.error-msg');
                    if (errorMsg) errorMsg.remove();
                }
            });

            return valid;
        }

        // autoTarikhUmur function (sama logic seperti create)
        function autoTarikhUmur() {
            const icInput = document.getElementById("nokp");
            const dobInput = document.getElementById("tarikhlahir");
            const ageInput = document.getElementById("umur");
            const genderInput = document.getElementById("jantina");
            const nokp = (icInput.value || "").replace(/\D/g, "");
            if (nokp.length < 6) {
                if (dobInput) dobInput.value = ''; if (ageInput) ageInput.value = ''; if (genderInput) genderInput.value = ''; return;
            }
            const yy = parseInt(nokp.slice(0, 2), 10);
            const mm = parseInt(nokp.slice(2, 4), 10);
            const dd = parseInt(nokp.slice(4, 6), 10);
            if (mm < 1 || mm > 12 || dd < 1 || dd > 31) { if (dobInput) dobInput.value = ''; if (ageInput) ageInput.value = ''; if (genderInput) genderInput.value = ''; return; }
            const today = new Date();
            const century = (yy <= (today.getFullYear() % 100)) ? 2000 : 1900;
            const year = century + yy;
            const candidate = new Date(year, mm - 1, dd);
            if (candidate.getFullYear() !== year || candidate.getMonth() !== (mm - 1) || candidate.getDate() !== dd) { if (dobInput) dobInput.value = ''; if (ageInput) ageInput.value = ''; if (genderInput) genderInput.value = ''; return; }
            const pad2 = n => String(n).padStart(2, '0');
            if (dobInput) dobInput.value = `${year}-${pad2(mm)}-${pad2(dd)}`;
            let age = today.getFullYear() - year;
            const belum = (today.getMonth() < (mm - 1)) || (today.getMonth() === (mm - 1) && today.getDate() < dd);
            if (belum) age -= 1;
            if (ageInput) ageInput.value = age;
            if (nokp.length >= 12 && genderInput) {
                const lastDigit = parseInt(nokp.charAt(11), 10);
                genderInput.value = (lastDigit % 2 === 1) ? 'Lelaki' : 'Perempuan';
            } else if (genderInput) {
                genderInput.value = '';
            }
        }

        // helper toggles for agama / bangsa
        function toggleAgamaLain() { const s = document.getElementById('agama'), i = document.getElementById('agamaLain'); if (!i || !s) return; i.style.display = (s.value === 'Lain') ? 'block' : 'none'; }
        function toggleBangsaLain() { const s = document.getElementById('bangsa'), i = document.getElementById('bangsaLain'); if (!i || !s) return; i.style.display = (s.value === 'Lain') ? 'block' : 'none'; }

        // tab click handlers
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', function () {
                const num = parseInt(this.getAttribute('data-tab'), 10);
                if (!this.classList.contains('disabled')) switchTab(num);
            });
        });

        // small behaviour: if nofile empty generate id (only if empty)
        window.onload = function () {
            const nf = document.getElementById('nofile');
            if (nf && !nf.value) {
                nf.value = "JKSP-" + Math.floor(Math.random() * 100000);
            }
        };

        // validateFinal used on submit
        function validateFinal() {
            if (!validateTab(6)) { switchTab(6); return false; }
            return true;
        }
    </script>
</body>

</html><?php /**PATH C:\laragon\www\testSpeksi\resources\views/patients/edit.blade.php ENDPATH**/ ?>