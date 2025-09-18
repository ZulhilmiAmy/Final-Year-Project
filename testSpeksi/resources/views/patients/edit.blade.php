{{-- resources/views/patients/edit.blade.php --}}
@php
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
@endphp

<!DOCTYPE html>
<html lang="ms">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pesakit - {{ $patient->nama }}</title>

    <style>
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
            background: #fff;
            border: 1px solid #cfd8dc; /* kelabu lembut */
            border-radius: 4px;
            padding: 8px 12px;
            margin-bottom: 20px;
        }

        .checkbox-group label {
            display: flex;              /* guna flex supaya kotak & teks align */
            align-items: center;        /* align tengah secara vertikal */
            margin: 6px 0;              /* spacing atas bawah */
            font-weight: normal;
            color: #000;                /* teks hitam */
            cursor: pointer;
            text-transform: none;
        }

        .checkbox-group input[type="checkbox"] {
            margin-right: 10px;         /* jarak antara kotak & teks */
            width: 16px;                /* size kotak seragam */
            height: 16px;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            cursor: pointer;
            gap: 6px;              /* jarak kecil antara kotak & teks */
        }


        .checkbox-label input[type="checkbox"] {
            margin: 0;             /* buang margin default browser */
            width: 16px;           /* boleh adjust size kalau perlu */
            height: 16px;
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
        <h1>Kemaskini Maklumat Pesakit</h1>

        @if(session('success'))
            <div style="background:#4caf50;color:#fff;padding:8px;border-radius:6px;margin-bottom:10px;">
                {{ session('success') }}
            </div>
        @endif

        {{-- Form update --}}
        <form id="patientForm" method="POST" action="{{ route('patients.update', $patient->id) }}"
            onsubmit="return validateFinal()">
            @csrf
            @method('PUT')

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
                                    value="{{ old('tarikh_rujukan', $patient->tarikh_rujukan) }}">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Tarikh Tindakbalas Awal</label>
                                <input id="tindakawal" type="date" name="tarikh_tindakbalas_awal"
                                    value="{{ old('tarikh_tindakbalas_awal', $patient->tarikh_tindakbalas_awal) }}">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>No. Fail JKSP</label>
                                <input id="nofile" name="no_fail" value="{{ old('no_fail', $patient->no_fail) }}">
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
                                <input id="nama" name="nama" value="{{ old('nama', $patient->nama) }}">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>No. KP</label>
                                <input id="nokp" name="no_kp" maxlength="12" value="{{ old('no_kp', $patient->no_kp) }}"
                                    oninput="autoTarikhUmur()">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Tarikh Lahir</label>
                                <input id="tarikhlahir" type="date" name="tarikh_lahir"
                                    value="{{ old('tarikh_lahir', $patient->tarikh_lahir) }}">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Umur</label>
                                <input id="umur" name="umur" value="{{ old('umur', $patient->umur) }}" readonly>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Jantina</label>
                                <input id="jantina" name="jantina" value="{{ old('jantina', $patient->jantina) }}"
                                    readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Agama</label>
                                <select id="agama" name="agama" onchange="toggleAgamaLain()">
                                    <option value="" disabled {{ old('agama', $patient->agama) ? '' : 'selected' }}>--
                                        Sila Pilih --</option>
                                    <option value="Islam" {{ (old('agama', $patient->agama) == 'Islam') ? 'selected' : '' }}>Islam</option>
                                    <option value="Buddha" {{ (old('agama', $patient->agama) == 'Buddha') ? 'selected' : '' }}>Buddha</option>
                                    <option value="Hindu" {{ (old('agama', $patient->agama) == 'Hindu') ? 'selected' : '' }}>Hindu</option>
                                    <option value="Kristian" {{ (old('agama', $patient->agama) == 'Kristian') ? 'selected' : '' }}>Kristian</option>
                                    <option value="Lain" {{ (old('agama', $patient->agama) == 'Lain') ? 'selected' : '' }}>Lain-lain</option>
                                </select>
                                <input id="agamaLain" name="agama_lain" placeholder="Sila nyatakan"
                                    style="display:{{ (old('agama_lain', $patient->agama_lain) ? 'block' : (old('agama', $patient->agama) == 'Lain' ? 'block' : 'none')) }}; margin-top:5px;"
                                    value="{{ old('agama_lain', $patient->agama_lain) }}">
                            </div>
                        </div>

                        <div class="form-col">
                            <div class="form-group">
                                <label>Bangsa</label>
                                <select id="bangsa" name="bangsa" onchange="toggleBangsaLain()">
                                    <option value="" disabled {{ old('bangsa', $patient->bangsa) ? '' : 'selected' }}>--
                                        Sila Pilih --</option>
                                    <option value="Melayu" {{ (old('bangsa', $patient->bangsa) == 'Melayu') ? 'selected' : '' }}>Melayu</option>
                                    <option value="Cina" {{ (old('bangsa', $patient->bangsa) == 'Cina') ? 'selected' : '' }}>Cina</option>
                                    <option value="India" {{ (old('bangsa', $patient->bangsa) == 'India') ? 'selected' : '' }}>India</option>
                                    <option value="Bumiputera Sabah" {{ (old('bangsa', $patient->bangsa) == 'Bumiputera Sabah') ? 'selected' : '' }}>Bumiputera Sabah</option>
                                    <option value="Bumiputera Sarawak" {{ (old('bangsa', $patient->bangsa) == 'Bumiputera Sarawak') ? 'selected' : '' }}>Bumiputera Sarawak</option>
                                    <option value="Lain" {{ (old('bangsa', $patient->bangsa) == 'Lain') ? 'selected' : '' }}>Lain-lain</option>
                                </select>
                                <input id="bangsaLain" name="bangsa_lain" placeholder="Sila nyatakan"
                                    style="display:{{ (old('bangsa_lain', $patient->bangsa_lain) ? 'block' : (old('bangsa', $patient->bangsa) == 'Lain' ? 'block' : 'none')) }}; margin-top:5px;"
                                    value="{{ old('bangsa_lain', $patient->bangsa_lain) }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea id="alamat" name="alamat" rows="2">{{ old('alamat', $patient->alamat) }}</textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Negeri</label>
                                <select id="negeri" name="negeri">
                                    <option value="" disabled {{ old('negeri', $patient->negeri) ? '' : 'selected' }}>--
                                        Sila Pilih --</option>
                                    @foreach(['Johor', 'Kedah', 'Kelantan', 'Melaka', 'Negeri Sembilan', 'Pahang', 'Perak', 'Perlis', 'Pulau Pinang', 'Sabah', 'Sarawak', 'Selangor', 'Terengganu', 'W.P. Kuala Lumpur', 'W.P. Labuan', 'W.P. Putrajaya'] as $n)
                                        <option value="{{ $n }}" {{ (old('negeri', $patient->negeri) == $n) ? 'selected' : '' }}>{{ $n }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-col">
                            <div class="form-group">
                                <label>Bandar</label>
                                <input id="bandar" name="bandar" value="{{ old('bandar', $patient->bandar) }}">
                            </div>
                        </div>

                        <div class="form-col">
                            <div class="form-group">
                                <label>Poskod</label>
                                <input id="poskod" name="poskod" maxlength="5"
                                    value="{{ old('poskod', $patient->poskod) }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>No. Tel.</label>
                                <input id="notel" name="no_tel" value="{{ old('no_tel', $patient->no_tel) }}">
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
                                    value="{{ old('tarikh_masuk_wad', $patient->tarikh_masuk_wad) }}">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Tarikh Dijangka Discaj</label>
                                <input id="tarikhdiscaj" type="date" name="tarikh_discaj"
                                    value="{{ old('tarikh_discaj', $patient->tarikh_discaj) }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Diagnosa</label>
                        <textarea id="diagnosa" name="diagnosa"
                            rows="2">{{ old('diagnosa', $patient->diagnosa) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Prognosis</label>
                        <select id="prognosis" name="prognosis">
                            <option value="" disabled {{ old('prognosis', $patient->prognosis) ? '' : 'selected' }}>
                                --Sila Pilih--</option>
                            <option value="Baik (Good)" {{ (old('prognosis', $patient->prognosis) == 'Baik (Good)') ? 'selected' : '' }}>Baik (Good)</option>
                            <option value="Sederhana (Fair)" {{ (old('prognosis', $patient->prognosis) == 'Sederhana (Fair)') ? 'selected' : '' }}>Sederhana (Fair)</option>
                            <option value="Tidak Baik (Poor)" {{ (old('prognosis', $patient->prognosis) == 'Tidak Baik (Poor)') ? 'selected' : '' }}>Tidak Baik (Poor)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Mobiliti Pesakit</label>
                        <select id="mobiliti" name="mobiliti">
                            <option value="" disabled {{ old('mobiliti', $patient->mobiliti) ? '' : 'selected' }}>--
                                Sila Pilih --</option>
                            <option value="Berjalan" {{ (old('mobiliti', $patient->mobiliti) == 'Berjalan') ? 'selected' : '' }}>Berjalan</option>
                            <option value="Kerusi Roda" {{ (old('mobiliti', $patient->mobiliti) == 'Kerusi Roda') ? 'selected' : '' }}>Kerusi Roda</option>
                            <option value="Crutches" {{ (old('mobiliti', $patient->mobiliti) == 'Crutches') ? 'selected' : '' }}>Crutches</option>
                            <option value="Walking Frame" {{ (old('mobiliti', $patient->mobiliti) == 'Walking Frame') ? 'selected' : '' }}>Walking Frame</option>
                            <option value="Bedridden" {{ (old('mobiliti', $patient->mobiliti) == 'Bedridden') ? 'selected' : '' }}>Bedridden</option>
                        </select>
                    </div>
                </div>

                <div class="button-group">
                    <button type="button" class="next-btn" onclick="next(1)">Seterusnya</button>
                </div>
            </div> <!-- end TAB1 -->

            {{-- TAB 2: Tujuan Rujukan --}}
            <div class="compartment" id="compartment2">
                <h3>Tujuan Rujukan</h3>

                @php
                // Ambil data lama (old input) atau data dari patient
                $bantuan = old('bantuan_praktik', $patient->bantuan_praktik ?? []);

                // Kalau bukan array tapi ada data
                if (!is_array($bantuan) && $bantuan !== null) {
                    // Cuba decode dari JSON
                    $tmp = json_decode($bantuan, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($tmp)) {
                        $bantuan = $tmp;
                    } else {
                        // Kalau bukan JSON, pecah ikut comma
                        $bantuan = array_filter(array_map('trim', explode(',', (string) $bantuan)));
                    }
                }

                // Sama untuk terapi sokongan
                $terapi = old('terapi_sokongan', $patient->terapi_sokongan ?? []);
                if (!is_array($terapi) && $terapi !== null) {
                    $tmp = json_decode($terapi, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($tmp)) {
                        $terapi = $tmp;
                    } else {
                        $terapi = array_filter(array_map('trim', explode(',', (string) $terapi)));
                    }
                }
            @endphp


                <div class="form-section">
                <div class="form-section-title">BANTUAN PRAKTIK</div>
                <div class="checkbox-group">
                    <label><input type="checkbox" name="bantuan_praktik[]" value="Pembiayaan Peralatan Perubatan"
                        {{ in_array('Pembiayaan Peralatan Perubatan', (array) $bantuan) ? 'checked' : '' }}>
                    PEMBIAYAAN PERALATAN PERUBATAN</label>
                    <label><input type="checkbox" name="bantuan_praktik[]" value="Bantuan Pembiayaan Rawatan"
                        {{ in_array('Bantuan Pembiayaan Rawatan', (array) $bantuan) ? 'checked' : '' }}>
                    BANTUAN PEMBIAYAAN RAWATAN</label>
                    <label><input type="checkbox" name="bantuan_praktik[]" value="Bantuan Pembiayaan Ubat-Ubatan"
                        {{ in_array('Bantuan Pembiayaan Ubat-Ubatan', (array) $bantuan) ? 'checked' : '' }}>
                    BANTUAN PEMBIAYAAN UBAT-UBATAN</label>
                    <label><input type="checkbox" name="bantuan_praktik[]" value="Bantuan Am"
                        {{ in_array('Bantuan Am', (array) $bantuan) ? 'checked' : '' }}>
                    BANTUAN AM</label>
                    <label><input type="checkbox" name="bantuan_praktik[]" value="Penempatan Institusi"
                        {{ in_array('Penempatan Institusi', (array) $bantuan) ? 'checked' : '' }}>
                    PENEMPATAN INSTITUSI</label>
                    <label><input type="checkbox" name="bantuan_praktik[]" value="Mengesan Waris"
                        {{ in_array('Mengesan Waris', (array) $bantuan) ? 'checked' : '' }}>
                    MENGESAN WARIS</label>
                </div>
                </div>

                {{-- TERAPI SOKONGAN --}}
                <div class="form-section">
                <div class="form-section-title">TERAPI SOKONGAN</div>
                <div class="checkbox-group">
                    <label><input type="checkbox" name="terapi_sokongan[]" value="Khidmat Perundingan"
                        {{ in_array('Khidmat Perundingan', (array) $terapi) ? 'checked' : '' }}>
                    KHIDMAT PERUNDINGAN</label>
                    <label><input type="checkbox" name="terapi_sokongan[]" value="Sokongan Emosi"
                        {{ in_array('Sokongan Emosi', (array) $terapi) ? 'checked' : '' }}>
                    SOKONGAN EMOSI</label>
                    <label><input type="checkbox" name="terapi_sokongan[]" value="Intervensi Krisis"
                        {{ in_array('Intervensi Krisis', (array) $terapi) ? 'checked' : '' }}>
                    INTERVENSI KRISIS</label>
                </div>
                </div>

                <div class="button-group">
                    <button type="button" class="back-btn" onclick="back(2)">Kembali</button>
                    <button type="button" class="next-btn" onclick="next(2)">Seterusnya</button>
                </div>
            </div>

            {{-- TAB 3: Kategori Kes --}}
            <div class="compartment" id="compartment3">
                <h3>Kategori Kes</h3>
                @php
                    $kategori = old('kategori_kes', $patient->kategori_kes ?? []);
                    if (!is_array($kategori) && $kategori !== null) {
                        $tmp = json_decode($kategori, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($tmp)) {
                            $kategori = $tmp;
                        } else {
                            $kategori = array_filter(array_map('trim', explode(',', (string) $kategori)));
                        }
                    }

                    // Senarai kategori rasmi
                    $kategoriOptions = [
                        'Penyakit Kronik',
                        'Penderaan Kanak-Kanak',
                        'Keganasan Rumah Tangga',
                        'Ibu Tanpa Nikah',
                        'Keganasan Seksual',
                        'Pesakit Terdampar',
                        'Masalah Tingkah Laku',
                        'HIV Positif / AIDS',
                        'Kegiatan Dadah / Alkohol',
                        'Cubaan Bunuh Diri',
                        'OKU',
                    ];
                @endphp

                <div class="form-row">
                    @foreach(array_chunk($kategoriOptions, ceil(count($kategoriOptions)/2)) as $chunk)
                        <div class="form-col">
                            <div class="checkbox-group">
                                @foreach($chunk as $option)
                                    <label>
                                        <input type="checkbox" name="kategori_kes[]" value="{{ $option }}"
                                            {{ in_array($option, $kategori) ? 'checked' : '' }}>
                                        {{ $option }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" id="lainCheck" 
                            name="kategori_kes[]" value="Lain-Lain"
                            {{ $patient->lain_lain || old('lain_lain') ? 'checked' : '' }}
                            onchange="toggleLainTextbox()">
                        <span>Lain-lain</span>
                    </label>

                    <input id="lain" name="lain_lain" type="text" 
                        placeholder="Sila nyatakan..."
                        value="{{ old('lain_lain', $patient->lain_lain) }}"
                        style="display: {{ old('lain_lain', $patient->lain_lain) ? 'block' : 'none' }}; 
                            margin-top:5px; width:100%;">
                </div>


                <div class="button-group">
                    <button type="button" class="back-btn" onclick="back(3)">Kembali</button>
                    <button type="button" class="next-btn" onclick="next(3)">Seterusnya</button>
                </div>
            </div>


            {{-- TAB 4: Maklumat Perujuk --}}
            <div class="compartment" id="compartment4">
                <h3>Maklumat Perujuk</h3>

                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label>Nama Perujuk</label>
                            <input id="namaperujuk" name="nama_perujuk"
                                value="{{ old('nama_perujuk', $patient->nama_perujuk) }}">
                        </div>
                    </div>

                    <div class="form-col">
                        <div class="form-group">
                            <label>Nama Disiplin</label>
                            <select id="disiplin" name="disiplin">
                                <option value="" disabled {{ old('disiplin', $patient->disiplin) ? '' : 'selected' }}>--
                                    Pilih Disiplin --</option>
                                @foreach(['MED', 'SURG', 'O&G', 'ORTHO', 'PAEDS', 'OFTAL', 'ENT', 'A&E', 'ONCHO', 'PSY', 'REHAB', 'NEFRO', 'URO', 'DERMA', 'NEURO', 'NEUROS', 'HAEMATO', 'CARDIO', 'ETC'] as $d)
                                    <option value="{{ $d }}" {{ (old('disiplin', $patient->disiplin) == $d) ? 'selected' : '' }}>{{ $d }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label>Punca Rujukan</label>
                            <select id="wadrujuk" name="wad_rujuk">
                                <option value="" disabled {{ old('wad_rujuk', $patient->wad_rujuk) ? '' : 'selected' }}>
                                    -- Pilih Lokasi --</option>
                                @foreach(['Wad', 'KLINIK', 'JPL', 'ED'] as $w)
                                    <option value="{{ $w }}" {{ (old('wad_rujuk', $patient->wad_rujuk) == $w) ? 'selected' : '' }}>{{ $w }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-col">
                        <div class="form-group">
                            <label>Diagnosis</label>
                            <input id="diagnosisrujuk" name="diagnosis_rujuk"
                                value="{{ old('diagnosis_rujuk', $patient->diagnosis_rujuk) }}">
                        </div>
                    </div>
                </div>

                <div class="button-group">
                    <button type="button" class="back-btn" onclick="back(4)">Kembali</button>
                    <button type="button" class="next-btn" onclick="next(4)">Seterusnya</button>
                </div>
            </div>

            {{-- TAB 5: Rujukan Agensi --}}
            <div class="compartment" id="compartment5">
                <h3>Rujukan Agensi</h3>

                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label>Nama Agensi</label>
                            <input id="agensi" name="agensi" value="{{ old('agensi', $patient->agensi) }}">
                        </div>
                    </div>

                    <div class="form-col">
                        <div class="form-group">
                            <label>Nama Pembekal</label>
                            <input id="pembekal" name="pembekal" value="{{ old('pembekal', $patient->pembekal) }}">
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label>Tarikh Laporan Dihantar</label>
                            <input type="date" name="tarikh_laporan"
                                value="{{ old('tarikh_laporan', $patient->tarikh_laporan) }}">
                        </div>
                    </div>

                    <div class="form-col">
                        <div class="form-group">
                            <label>Tarikh Dokumen Lengkap Diterima</label>
                            <input type="date" name="tarikh_dokumen_lengkap"
                                value="{{ old('tarikh_dokumen_lengkap', $patient->tarikh_dokumen_lengkap) }}">
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label>Item Dipohon</label>
                            <input name="item_dipohon" value="{{ old('item_dipohon', $patient->item_dipohon) }}">
                        </div>
                    </div>

                    <div class="form-col">
                        <div class="form-group">
                            <label>Tarikh Kelulusan</label>
                            <input type="date" name="tarikh_kelulusan"
                                value="{{ old('tarikh_kelulusan', $patient->tarikh_kelulusan) }}">
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label>Tanggungan Pesakit (RM)</label>
                            <input type="number" step="0.01" name="tanggungan"
                                value="{{ old('tanggungan', $patient->tanggungan) }}">
                        </div>
                    </div>

                    <div class="form-col">
                        <div class="form-group">
                            <label>Jumlah Dipohon (RM)</label>
                            <input type="number" step="0.01" name="jumlah_dipohon"
                                value="{{ old('jumlah_dipohon', $patient->jumlah_dipohon) }}">
                        </div>
                    </div>

                    <div class="form-col">
                        <div class="form-group">
                            <label>Jumlah Kelulusan (RM)</label>
                            <input type="number" step="0.01" name="jumlah_kelulusan"
                                value="{{ old('jumlah_kelulusan', $patient->jumlah_kelulusan) }}">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Tarikh Tuntut</label>
                    <input type="date" name="tarikh_tuntut" value="{{ old('tarikh_tuntut', $patient->tarikh_tuntut) }}">
                </div>

                <div class="button-group">
                    <button type="button" class="back-btn" onclick="back(5)">Kembali</button>
                    <button type="button" class="next-btn" onclick="next(5)">Seterusnya</button>
                </div>
            </div>

            {{-- TAB 6: Temu Janji --}}
            <div class="compartment" id="compartment6">
                <h3>Tarikh Temu Janji</h3>

                <div id="calendar-container">
                    <div id="calendarTab6"></div>
                </div>

                <div class="form-group">
                    <label>Pegawai Kes</label>
                    <input id="pegawaikes" name="pegawai_kes" value="{{ old('pegawai_kes', $patient->pegawai_kes) }}">
                </div>

                <div class="form-group">
                    <label>Tarikh Temu Janji</label>
                    <input id="tarikhtemujanji" type="date" name="tarikh_temu"
                        value="{{ old('tarikh_temu', $patient->tarikh_temu) }}">
                </div>

                <div class="form-group">
                    <label>Masa Temu Janji</label>
                    <input id="masatemujanji" type="time" name="masa_temu"
                        value="{{ old('masa_temu', $patient->masa_temu) }}">
                </div>

                <div class="form-group">
                    <label>Catatan Temu Janji</label>
                    <textarea id="catatantemujanji" name="catatan_temu"
                        rows="3">{{ old('catatan_temu', $patient->catatan_temu) }}</textarea>
                </div>

                <div class="button-group">
                    <button type="button" class="back-btn" onclick="back(6)">Kembali</button>
                    <button type="submit" class="save-btn">Kemaskini</button>
                </div>
            </div>

        </form> <!-- end form -->
    </div> <!-- end container -->

<script>
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
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                buttonText: {
                    today: 'Hari Ini',
                    month: 'Bulan',
                    week: 'Minggu',
                    day: 'Hari'
                },
                events: @json($events),
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

        // Input handlers
        document.querySelectorAll("input, textarea, select").forEach(el => {
            el.addEventListener("input", removeError);
            el.addEventListener("change", removeError);
        });

        function removeError() {
            this.classList.remove("error");
            let errorMsg = this.parentNode.querySelector(".error-msg");
            if (errorMsg) errorMsg.remove();
        }

        // autoTarikhUmur run if IC already present
        const icInput = document.getElementById("nokp");
        if (icInput && icInput.value.trim() !== "") autoTarikhUmur();

        // tab click handlers
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', function () {
                const num = parseInt(this.getAttribute('data-tab'), 10);
                switchTab(num);
            });
        });

        // small behaviour: if nofile empty generate id
        const nf = document.getElementById('nofile');
        if (nf && !nf.value) nf.value = "JKSP-" + Math.floor(Math.random() * 100000);
    });

    function switchTab(num) {
        document.querySelectorAll('.compartment').forEach(c => c.classList.remove('active'));
        const target = document.querySelector(`#compartment${num}`);
        if (target) target.classList.add('active');
        document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        const tab = document.querySelector(`.tab[data-tab="${num}"]`);
        if (tab) tab.classList.add('active');

        // highlight Tab 2 jika belum lengkap
        if (num > 2) validateTab(2);

        // FullCalendar redraw
        if (num === 6 && calendar) {
            setTimeout(() => {
                calendar.updateSize();
                calendar.render();
                calendar.changeView('dayGridMonth');
            }, 200);
        }
    }

    // NEXT tanpa block
    function next(num) {
        document.querySelector(`.tab[data-tab="${num + 1}"]`)?.classList.remove('disabled');
        switchTab(num + 1);
    }

    function back(num) {
        switchTab(num - 1);
    }

    function validateTab(num) {
        let valid = true;
        let inputs = document.querySelectorAll(`#compartment${num} input, #compartment${num} textarea, #compartment${num} select`);

        // Tab 2 special checks
        if (num === 2) {
            const bantuanGroup = document.querySelectorAll('#compartment2 .bantuanpraktik');
            const terapiGroup = document.querySelectorAll('#compartment2 .terapisokongan');
            let bantuanChecked = [...bantuanGroup].some(cb => cb.checked);
            let terapiChecked = [...terapiGroup].some(cb => cb.checked);

            if (!bantuanChecked) {
                valid = false;
                bantuanGroup[0]?.closest('.checkbox-group')?.style.setProperty("border", "2px solid red", "important");
            } else bantuanGroup[0]?.closest('.checkbox-group')?.style.removeProperty("border");

            if (!terapiChecked) {
                valid = false;
                terapiGroup[0]?.closest('.checkbox-group')?.style.setProperty("border", "2px solid red", "important");
            } else terapiGroup[0]?.closest('.checkbox-group')?.style.removeProperty("border");
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
            const lainCheck = document.getElementById('lainCheck');
            const lainInput = document.getElementById('lain');
            if (lainCheck && lainCheck.checked && lainInput && !lainInput.value.trim()) {
                valid = false;
                lainInput.classList.add('error');
            }
        }

        // basic required check
        inputs.forEach(input => {
            if (!input.hasAttribute('name')) return;
            if (input.offsetParent === null) return;
            if (input.type === 'checkbox') return;
            if (!input.value.trim()) {
                valid = false;
                input.classList.add('error');
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

    function autoTarikhUmur() {
        const icInput = document.getElementById("nokp");
        const dobInput = document.getElementById("tarikhlahir");
        const ageInput = document.getElementById("umur");
        const genderInput = document.getElementById("jantina");
        const nokp = (icInput.value || "").replace(/\D/g, "");
        if (nokp.length < 6) {
            if (dobInput) dobInput.value = '';
            if (ageInput) ageInput.value = '';
            if (genderInput) genderInput.value = '';
            return;
        }
        const yy = parseInt(nokp.slice(0, 2), 10);
        const mm = parseInt(nokp.slice(2, 4), 10);
        const dd = parseInt(nokp.slice(4, 6), 10);
        if (mm < 1 || mm > 12 || dd < 1 || dd > 31) {
            if (dobInput) dobInput.value = '';
            if (ageInput) ageInput.value = '';
            if (genderInput) genderInput.value = '';
            return;
        }
        const today = new Date();
        const century = (yy <= (today.getFullYear() % 100)) ? 2000 : 1900;
        const year = century + yy;
        const candidate = new Date(year, mm - 1, dd);
        if (candidate.getFullYear() !== year || candidate.getMonth() !== (mm - 1) || candidate.getDate() !== dd) {
            if (dobInput) dobInput.value = '';
            if (ageInput) ageInput.value = '';
            if (genderInput) genderInput.value = '';
            return;
        }
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

    function toggleAgamaLain() {
        const s = document.getElementById('agama'),
            i = document.getElementById('agamaLain');
        if (!i || !s) return;
        i.style.display = (s.value === 'Lain') ? 'block' : 'none';
    }

    function toggleBangsaLain() {
        const s = document.getElementById('bangsa'),
            i = document.getElementById('bangsaLain');
        if (!i || !s) return;
        i.style.display = (s.value === 'Lain') ? 'block' : 'none';
    }

    function validateFinal() {
        if (!validateTab(6)) {
            switchTab(6);
            return false;
        }
        return true;
    }
    function toggleLainTextbox() {
        const check = document.getElementById("lainCheck");
        const textbox = document.getElementById("lain");
        if (check && textbox) {
            textbox.style.display = check.checked ? "block" : "none";
            if (check.checked) {
                textbox.focus();
            } else {
                textbox.value = ""; // optional: clear bila uncheck
            }
        }
    }

</script>

</body>

</html>