{{-- resources/views/patients/create.blade.php --}}
<!DOCTYPE html>
<html lang="ms">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPeKSi - Sistem Maklumat Pesakit</title>
    <style>
        /* CSS yang sudah ada */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: linear-gradient(to bottom right, #e6f2f8, #f0f6fa);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .header {
            background-color: #111;
            color: white;
            text-align: center;
        }

        .header img {
            width: 100%;
            height: auto;
            display: block;
            max-height: 140px;
            object-fit: cover;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            background-color: white;
            padding: 10px 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .container h1 {
            margin: 5px 0 10px 0;
            color: #333;
            font-size: x-large;
        }

        .compartment {
            display: none;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            background-color: #f9f9f9;
            margin-bottom: 20px;
        }

        .compartment.active {
            display: block;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
        }

        input[type="checkbox"] {
            width: auto;
            margin-right: 8px;
        }

        .button-group {
            margin-top: 20px;
            text-align: right;
        }

        button {
            padding: 10px 20px;
            margin-left: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        .save-btn {
            background-color: #4CAF50;
            color: white;
        }

        .next-btn {
            background-color: #2196F3;
            color: white;
        }

        .back-btn {
            background-color: #f44336;
            color: white;
        }

        .save-btn:hover {
            background-color: #3e8e41;
        }

        .next-btn:hover {
            background-color: #0b7dda;
        }

        .back-btn:hover {
            background-color: #da190b;
        }

        .tab-container {
            display: flex;
            flex-wrap: wrap;
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
        }

        .tab {
            padding: 10px 15px;
            background-color: #f1f1f1;
            border: 1px solid #ddd;
            border-bottom: none;
            border-radius: 5px 5px 0 0;
            margin-right: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .tab.active {
            background-color: #f9f9f9;
            font-weight: bold;
            color: #2196F3;
        }

        .tab.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .category-title {
            font-weight: bold;
            background-color: #eee;
            padding: 5px 8px;
            border-radius: 4px;
            display: inline-block;
            margin-bottom: 5px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f9fa;
            padding: 16px 20px;
            border-bottom: 2px solid #204d84;
            font-size: 14px;
        }

        .breadcrumbs a {
            color: #204d84;
            text-decoration: none;
            font-weight: 500;
        }

        .breadcrumbs a:hover {
            text-decoration: underline;
        }

        .breadcrumbs span {
            color: #555;
            font-weight: bold;
        }

        /* Layout improvements */
        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px 15px -10px;
        }

        .form-col {
            flex: 1;
            padding: 0 10px;
            min-width: 200px;
        }

        .form-section {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px dashed #ddd;
        }

        .form-section-title {
            font-size: 16px;
            color: #204d84;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 2px solid #204d84;
            font-weight: bold;
        }

        .checkbox-group {
            background: #f8f8f8;
            padding: 12px;
            border-radius: 5px;
            border: 1px solid #eee;
            margin-bottom: 15px;
        }

        .checkbox-group label {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            font-weight: normal;
        }

        .small-date-input {
            width: 50% !important;
            max-width: 200px;
        }

        @media (max-width: 768px) {
            .form-col {
                flex: 100%;
            }

            .small-date-input {
                width: 100% !important;
                max-width: 100%;
            }
        }

        #notif {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #4CAF50;
            color: white;
            padding: 12px 20px;
            border-radius: 6px;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
            display: none;
            z-index: 1000;
        }

        /* Calendar container styling */
        #calendar-container {
            margin: 20px 0;
            position: relative;
        }

        #calendar {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 15px;
        }

        /* Style untuk event dalam kalender */
        .fc-event {
            cursor: pointer;
            border-radius: 4px;
            margin: 2px 0;
        }

        #mobiliti option[value="Crutches"],
        #mobiliti option[value="Walking Frame"],
        #mobiliti option[value="Bedridden"] {
            font-style: italic;
        }

        input.error,
        select.error,
        textarea.error {
            border: 2px solid red !important;
            background: #ffe6e6;
        }

        input,
        textarea,
        select {
            text-transform: uppercase;
        }

        /* Tambah dalam style */
        .checkbox-group label {
            text-transform: uppercase;
        }
    </style>

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
</head>

<body>

    <div id="notif">Data berjaya disimpan! ✅</div>

    <div class="header">
        <img src="https://raw.githubusercontent.com/ZulhilmiAmy/Final-Year-Project/main/Image%20banner/Banner-SPeKSi.png"
            alt="Banner">
    </div>

    <div class="top-bar">
        <div class="breadcrumbs">
            <a>Login</a> &gt;
            <a href="{{ route('home') }}" onclick="return confirmHome(event)">Halaman Utama</a> &gt;
            <a>Sistem Maklumat Pesakit</a>
        </div>
    </div>

    <div class="container">
        <h1>Sistem Maklumat Pesakit</h1>

        {{-- show flash success --}}
        @if(session('success'))
            <div style="background:#4caf50;color:#fff;padding:8px;border-radius:6px;margin-bottom:10px;">
                {{ session('success') }}
            </div>
        @endif

        {{-- Start form: we wrap everything so the "Hantar Semua" dapat submit to server --}}
        <form id="patientForm" method="POST" action="{{ route('patients.store') }}" onsubmit="return validateFinal()">
            @csrf

            <!-- TAB nav and compartments (copy your HTML structure exactly) -->
            <div class="tab-container">
                <div class="tab active" data-tab="1">1. Maklumat Peribadi</div>
                <div class="tab disabled" data-tab="2">2. Tujuan Rujukan</div>
                <div class="tab disabled" data-tab="3">3. Kategori Kes</div>
                <div class="tab disabled" data-tab="4">4. Maklumat Perujuk</div>
                <div class="tab disabled" data-tab="5">5. Rujukan Agensi</div>
                <div class="tab disabled" data-tab="6">6. Tarikh Temu Janji</div>
            </div>

            <!-- TAB 1 -->
            <div class="compartment active" id="compartment1">
                <h3>Maklumat Peribadi</h3>
                <div class="form-section">
                    <div class="form-section-title">Maklumat Rujukan</div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group"> <label>Tarikh Rujukan Diterima</label> <input type="date"
                                    id="tarikhrujukan" name="tarikh_rujukan"> </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group"> <label>Tarikh Tindakbalas Awal</label> <input type="date"
                                    id="tindakawal" name="tarikh_tindakbalas_awal"> </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group"> <label>No. Fail JKSP</label> <input id="nofile" name="no_fail"
                                    readonly> </div>
                        </div>
                    </div>
                </div>
                <div class="form-section">
                    <div class="form-section-title">Maklumat Asas Pesakit</div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group"> <label>Nama</label> <input id="nama" name="nama"> </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group"> <label>No. KP</label> <input id="nokp" name="no_kp" maxlength="12"
                                    value="{{ request('kp') }}" placeholder="Cth: 010203101234"
                                    oninput="autoTarikhUmur()"> </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group"> <label>Tarikh Lahir</label> <input type="date" id="tarikhlahir"
                                    name="tarikh_lahir" readonly> </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group"> <label>Umur</label> <input id="umur" name="umur" readonly> </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group"> <label>Jantina</label> <input id="jantina" name="jantina" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group"> <label>Agama</label> <select id="agama" name="agama"
                                    onchange="toggleAgamaLain()">
                                    <option value="" disabled selected hidden>-- Sila Pilih --</option>
                                    <option value="Islam">Islam</option>
                                    <option value="Buddha">Buddha</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Kristian">Kristian</option>
                                    <option value="Lain">Lain-lain</option>
                                </select> <input type="text" id="agamaLain" name="agama_lain"
                                    placeholder="Sila nyatakan" style="display:none; margin-top:5px;"> </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group"> <label>Bangsa</label> <select id="bangsa" name="bangsa"
                                    onchange="toggleBangsaLain()">
                                    <option value="" disabled selected hidden>-- Sila Pilih --</option>
                                    <option value="Melayu">Melayu</option>
                                    <option value="Cina">Cina</option>
                                    <option value="India">India</option>
                                    <option value="Bumiputera Sabah">Bumiputera Sabah</option>
                                    <option value="Bumiputera Sarawak">Bumiputera Sarawak</option>
                                    <option value="Lain">Lain-lain</option>
                                </select> <input type="text" id="bangsaLain" name="bangsa_lain"
                                    placeholder="Sila nyatakan" style="display:none; margin-top:5px;"> </div>
                        </div>
                    </div>
                    <div class="form-section-title">Alamat</div>
                    <div class="form-group"> <label>Alamat</label> <textarea id="alamat" name="alamat" rows="2"
                            placeholder="Alamat Jalan / Taman / No Rumah"></textarea> </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group"> <label>Negeri</label> <select id="negeri" name="negeri">
                                    <option value="" disabled selected hidden>-- Sila Pilih --</option>
                                    <option>Johor</option>
                                    <option>Kedah</option>
                                    <option>Kelantan</option>
                                    <option>Melaka</option>
                                    <option>Negeri Sembilan</option>
                                    <option>Pahang</option>
                                    <option>Perak</option>
                                    <option>Perlis</option>
                                    <option>Pulau Pinang</option>
                                    <option>Sabah</option>
                                    <option>Sarawak</option>
                                    <option>Selangor</option>
                                    <option>Terengganu</option>
                                    <option>W.P. Kuala Lumpur</option>
                                    <option>W.P. Labuan</option>
                                    <option>W.P. Putrajaya</option>
                                </select> </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group"> <label>Bandar</label> <input id="bandar" name="bandar"> </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group"> <label>Poskod</label> <input id="poskod" maxlength="5"
                                    name="poskod"> </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group"> <label>No. Tel.</label> <input id="notel" name="no_tel"> </div>
                        </div>
                    </div>
                </div>
                <div class="form-section">
                    <div class="form-section-title">Maklumat Perubatan</div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group"> <label>Tarikh Masuk Wad</label> <input type="date" id="tarikhmasuk"
                                    name="tarikh_masuk_wad"> </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group"> <label>Tarikh Dijangka Discaj</label> <input type="date"
                                    id="tarikhdiscaj" name="tarikh_discaj"> </div>
                        </div>
                    </div>
                    <div class="form-group"> <label>Diagnosa</label> <textarea id="diagnosa" name="diagnosa"
                            rows="2"></textarea> </div>
                    <div class="form-group"> <label>Prognosis</label> <select id="prognosis" name="prognosis">
                            <option value="" disabled selected hidden>--Sila Pilih--</option>
                            <option value="Baik (Good)">Baik (Good)</option>
                            <option value="Sederhana (Fair)">Sederhana (Fair)</option>
                            <option value="Tidak Baik (Poor)">Tidak Baik (Poor)</option>
                        </select> </div>
                    <div class="form-group"> <label>Mobiliti Pesakit</label> <select id="mobiliti" name="mobiliti">
                            <option value="" disabled selected hidden>-- Sila Pilih --</option>
                            <option value="Berjalan">Berjalan</option>
                            <option value="Kerusi Roda">Kerusi Roda</option>
                            <option value="Crutches">Crutches</option>
                            <option value="Walking Frame">Walking Frame</option>
                            <option value="Bedridden">Bedridden</option>
                        </select> </div>
                </div>
                <div class="button-group"> <button type="button" class="save-btn" onclick="simpan(1)">Simpan</button>
                    <button type="button" class="next-btn" onclick="next(1)">Seterusnya</button>
                </div>
            </div>
            <!-- TAB 2 -->
            <div class="compartment" id="compartment2">
                <h3>Tujuan Rujukan</h3>

                <div class="form-section">
                    <div class="form-section-title">BANTUAN PRAKTIK</div>
                    <div class="checkbox-group">
                        <label><input type="checkbox" class="bantuanpraktik" name="bantuan_praktik[]"
                                value="Pembiayaan Peralatan Perubatan"> Pembiayaan Peralatan Perubatan</label>
                        <label><input type="checkbox" class="bantuanpraktik" name="bantuan_praktik[]"
                                value="Bantuan Pembiayaan Rawatan"> Bantuan Pembiayaan Rawatan</label>
                        <label><input type="checkbox" class="bantuanpraktik" name="bantuan_praktik[]"
                                value="Bantuan Pembiayaan Ubat-Ubatan"> Bantuan Pembiayaan Ubat-Ubatan</label>
                        <label><input type="checkbox" class="bantuanpraktik" name="bantuan_praktik[]"
                                value="Bantuan Am"> Bantuan Am</label>
                        <label><input type="checkbox" class="bantuanpraktik" name="bantuan_praktik[]"
                                value="Penempatan Institusi"> Penempatan Institusi</label>
                        <label><input type="checkbox" class="bantuanpraktik" name="bantuan_praktik[]"
                                value="Mengesan Waris"> Mengesan Waris</label>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">TERAPI SOKONGAN</div>
                    <div class="checkbox-group">
                        <label><input type="checkbox" class="terapisokongan" name="terapi_sokongan[]"
                                value="Khidmat Perundingan"> Khidmat Perundingan</label>
                        <label><input type="checkbox" class="terapisokongan" name="terapi_sokongan[]"
                                value="Sokongan Emosi"> Sokongan Emosi</label>
                        <label><input type="checkbox" class="terapisokongan" name="terapi_sokongan[]"
                                value="Intervensi Krisis"> Intervensi Krisis</label>
                    </div>
                </div>

                <div class="button-group">
                    <button type="button" class="back-btn" onclick="back(2)">Kembali</button>
                    <button type="button" class="save-btn" onclick="simpan(2)">Simpan</button>
                    <button type="button" class="next-btn" onclick="next(2)">Seterusnya</button>
                </div>
            </div>

            <!-- TAB 3 -->
            <div class="compartment" id="compartment3">
                <h3>Kategori Kes</h3>

                <div class="form-row">
                    <div class="form-col">
                        <div class="checkbox-group">
                            <label><input type="checkbox" name="kategori_kes[]" value="Penyakit Kronik"> Penyakit
                                Kronik</label>
                            <label><input type="checkbox" name="kategori_kes[]" value="Penderaan Kanak-kanak"> Penderaan
                                Kanak-kanak</label>
                            <label><input type="checkbox" name="kategori_kes[]" value="Keganasan Rumah Tangga">
                                Keganasan Rumah Tangga</label>
                            <label><input type="checkbox" name="kategori_kes[]" value="Ibu Tanpa Nikah"> Ibu Tanpa
                                Nikah</label>
                            <label><input type="checkbox" name="kategori_kes[]" value="Keganasan Seksual"> Keganasan
                                Seksual</label>
                            <label><input type="checkbox" name="kategori_kes[]" value="Pesakit Terdampar"> Pesakit
                                Terdampar</label>
                        </div>
                    </div>

                    <div class="form-col">
                        <div class="checkbox-group">
                            <label><input type="checkbox" name="kategori_kes[]" value="Masalah Tingkah Laku"> Masalah
                                Tingkah Laku</label>
                            <label><input type="checkbox" name="kategori_kes[]" value="HIV POSITIF / AIDS"> HIV POSITIF
                                / AIDS</label>
                            <label><input type="checkbox" name="kategori_kes[]" value="Dadah / Alkohol"> Dadah /
                                Alkohol</label>
                            <label><input type="checkbox" name="kategori_kes[]" value="Cubaan Bunuh Diri"> Cubaan Bunuh
                                Diri</label>
                            <label><input type="checkbox" name="kategori_kes[]" value="OKU"> OKU</label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label><input type="checkbox" id="lainCheck" onclick="toggleLainTextbox()"> Lain-lain</label>
                    <input id="lain" name="lain_lain" type="text" placeholder="Sila nyatakan"
                        style="display:none; margin-top:5px;">
                </div>

                <div class="button-group">
                    <button type="button" class="back-btn" onclick="back(3)">Kembali</button>
                    <button type="button" class="save-btn" onclick="simpan(3)">Simpan</button>
                    <button type="button" class="next-btn" onclick="next(3)">Seterusnya</button>
                </div>
            </div>

            <!-- TAB 4 -->
            <div class="compartment" id="compartment4">
                <h3>Maklumat Perujuk</h3>

                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label>Nama Perujuk</label>
                            <input id="namaperujuk" name="nama_perujuk">
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label>Nama Disiplin</label>
                            <select id="disiplin" name="disiplin">
                                <option value="" disabled selected hidden>-- Pilih Disiplin --</option>
                                <option>MED</option>
                                <option>SURG</option>
                                <option>O&amp;G</option>
                                <option>ORTHO</option>
                                <option>PAEDS</option>
                                <option>OFTAL</option>
                                <option>ENT</option>
                                <option>A&amp;E</option>
                                <option>ONCHO</option>
                                <option>PSY</option>
                                <option>REHAB</option>
                                <option>NEFRO</option>
                                <option>URO</option>
                                <option>DERMA</option>
                                <option>NEURO</option>
                                <option>NEUROS</option>
                                <option>HAEMATO</option>
                                <option>CARDIO</option>
                                <option>ETC</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label>Punca Rujukan</label>
                            <select id="wadrujuk" name="wad_rujuk">
                                <option value="" disabled selected hidden>-- Pilih Lokasi --</option>
                                <option>Wad</option>
                                <option>KLINIK</option>
                                <option>JPL</option>
                                <option>ED</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label>Diagnosis</label>
                            <input id="diagnosisrujuk" name="diagnosis_rujuk">
                        </div>
                    </div>
                </div>

                <div class="button-group">
                    <button type="button" class="back-btn" onclick="back(4)">Kembali</button>
                    <button type="button" class="save-btn" onclick="simpan(4)">Simpan</button>
                    <button type="button" class="next-btn" onclick="next(4)">Seterusnya</button>
                </div>
            </div>

            <!-- TAB 5 -->
            <div class="compartment" id="compartment5">
                <h3>Rujukan Agensi</h3>

                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label>Nama Agensi</label>
                            <input id="agensi" name="agensi">
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label>Nama Pembekal</label>
                            <input id="pembekal" name="pembekal">
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label>Tarikh Laporan Dihantar</label>
                            <input type="date" id="tarikhlaporan" name="tarikh_laporan">
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label>Tarikh Dokumen Lengkap Diterima</label>
                            <input type="date" id="tarikhdoklengkap" name="tarikh_dokumen_lengkap">
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label>Item Dipohon</label>
                            <input id="item" name="item_dipohon">
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label>Tarikh Kelulusan</label>
                            <input type="date" id="tarikhlulus" name="tarikh_kelulusan">
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label>Tanggungan Pesakit (RM)</label>
                            <input type="number" id="tanggung" name="tanggungan" step="0.01">
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label>Jumlah Dipohon (RM)</label>
                            <input type="number" id="dipohon" name="jumlah_dipohon" step="0.01">
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label>Jumlah Kelulusan (RM)</label>
                            <input type="number" id="lulus" name="jumlah_kelulusan" step="0.01">
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label>Tarikh Tuntutan</label>
                            <input type="date" id="tarikhtuntut" name="tarikh_tuntut" class="small-date-input">
                        </div>
                    </div>
                </div>

                <div class="button-group">
                    <button type="button" class="back-btn" onclick="back(5)">Kembali</button>
                    <button type="button" class="save-btn" onclick="simpan(5)">Simpan</button>
                    <button type="button" class="next-btn" onclick="next(5)">Seterusnya</button>
                </div>
            </div>

            <!-- TAB 6 -->
            <div class="compartment" id="compartment6">
                <h3>Tarikh Temu Janji</h3>

                <div id="calendar-container">
                    <div id="calendarTab6"></div>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label>Pegawai Kes</label>
                            <input id="pegawaikes" name="pegawai_kes">
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label>Tarikh Temu Janji</label>
                            <input type="date" id="tarikhtemujanji" name="tarikh_temu">
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label>Masa Temu Janji</label>
                            <input type="time" id="masatemujanji" name="masa_temu">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Catatan Temu Janji</label>
                    <textarea id="catatantemujanji" name="catatan_temu" rows="3"
                        placeholder="Catatan tambahan untuk temu janji"></textarea>
                </div>

                <div class="button-group">
                    <button type="button" class="back-btn" onclick="back(6)">Kembali</button>
                    <button type="button" class="save-btn" onclick="simpanTemujanji()">Simpan Temu Janji</button>

                    <!-- This is the final submit that stores everything to server -->
                    <button type="submit" class="next-btn">Hantar Semua</button>
                </div>
            </div>
        </form> <!-- end form -->

    </div>

    <script>

        let data = {};
        let calendar;

        // Fungsi-fungsi yang sudah ada
        function confirmGoLogin() {
            if (confirm("Adakah anda pasti mahu kembali ke halaman Login?")) {
                window.location.href = "{{ route('login.custom') }}";
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
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
        });

        function toggleLainTextbox() {
            const lainCheckbox = document.getElementById('lainCheck');
            const lainTextbox = document.getElementById('lain');
            lainTextbox.style.display = lainCheckbox.checked ? 'block' : 'none';
        }

        function switchTab(num) {
            document.querySelectorAll('.compartment').forEach(c => c.classList.remove('active'));
            document.querySelector(`#compartment${num}`).classList.add('active');
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            document.querySelector(`.tab[data-tab="${num}"]`).classList.add('active');
            document.querySelector(`.tab[data-tab="${num}"]`).classList.remove('disabled');

            // ✅ bila buka Tab 6, paksa calendar redraw
            if (num === 6 && calendar) {
                setTimeout(() => {
                    calendar.updateSize();
                }, 200);
            }
        }

        function next(num) {
            if (!validateTab(num)) return; // ❌ kalau ada error, jangan proceed
            simpan(num, true);
            document.querySelector(`.tab[data-tab="${num + 1}"]`).classList.remove('disabled');
            switchTab(num + 1);
        }

        function back(num) {
            simpan(num, false); // Simpan tapi JANGAN tunjuk notifikasi
            switchTab(num - 1);
        }

        function simpan(num, showNotif = true) {
            let inputs = document.querySelectorAll(`#compartment${num} input, #compartment${num} textarea, #compartment${num} select`);
            let valid = true;

            inputs.forEach(i => {
                if (!i.hasAttribute("name")) return; // skip yang tiada name
                if (i.offsetParent === null) return; // skip hidden
                if (!i.hasAttribute("required")) return; // skip kalau bukan required

                if (i.type === "checkbox") {
                    const group = document.querySelectorAll(`#compartment${num} input[name="${i.name}"]`);
                    if (group.length > 0 && [...group].every(cb => !cb.checked)) {
                        valid = false;
                        i.closest('.checkbox-group')?.style.setProperty("border", "2px solid red", "important");
                    } else {
                        i.closest('.checkbox-group')?.style.removeProperty("border");
                    }
                } else if (!i.value.trim()) {
                    valid = false;
                    i.classList.add("error");
                } else {
                    i.classList.remove("error");
                }
            });

            if (!valid) {
                // ❌ jangan popup alert lagi, biar error message per-field
                return false;
            }

            // ✅ Kalau semua valid → simpan data
            inputs.forEach(i => {
                if (i.type === "checkbox") {
                    data[i.id || i.className] = i.checked ? "Ya" : "Tidak";
                } else {
                    data[i.id] = i.value;
                }
            });

            if (showNotif) {
                const notif = document.getElementById("notif");
                notif.innerText = `Bahagian ${num} berjaya disimpan! ✅`;
                notif.style.display = "block";
                setTimeout(() => {
                    notif.style.display = "none";
                }, 2000);
            }

            return true;
        }

        function hantar() {
            simpan(6);
            console.log("Semua data dihantar:", data);
            alert("Semua maklumat telah dihantar!");
        }

        // Auto-generate No. Fail JKSP bila page load
        window.onload = function () {
            let autoId = "JKSP-" + Math.floor(Math.random() * 100000);
            document.getElementById("nofile").value = autoId;
        };

        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', function () {
                let tabNum = parseInt(this.dataset.tab);
                if (!this.classList.contains('disabled')) {
                    switchTab(tabNum);
                }
            });
        });

        function autoTarikhUmur() {
            const icInput = document.getElementById("nokp");
            const dobInput = document.getElementById("tarikhlahir");
            const ageInput = document.getElementById("umur");
            const genderInput = document.getElementById("jantina");

            // Terima nombor dengan/ tanpa sengkang, ambil hanya digit
            const nokp = (icInput.value || "").replace(/\D/g, "");

            // Jika kurang 6 digit (YYMMDD) — kosongkan
            if (nokp.length < 6) {
                dobInput.value = "";
                ageInput.value = "";
                genderInput.value = "";
                return;
            }

            const yy = parseInt(nokp.slice(0, 2), 10);
            const mm = parseInt(nokp.slice(2, 4), 10);
            const dd = parseInt(nokp.slice(4, 6), 10);

            // Validasi asas bulan & hari
            if (mm < 1 || mm > 12 || dd < 1 || dd > 31) {
                dobInput.value = "";
                ageInput.value = "";
                genderInput.value = "";
                return;
            }

            const today = new Date();
            const century = (yy <= (today.getFullYear() % 100)) ? 2000 : 1900;
            const year = century + yy;

            // Bina tarikh & sahkan (elak 31/02 dsb.)
            const candidate = new Date(year, mm - 1, dd);
            if (
                candidate.getFullYear() !== year ||
                candidate.getMonth() !== (mm - 1) ||
                candidate.getDate() !== dd
            ) {
                dobInput.value = "";
                ageInput.value = "";
                genderInput.value = "";
                return;
            }

            // Format tempatan untuk <input type="date"> (YYYY-MM-DD)
            const pad2 = n => String(n).padStart(2, "0");
            dobInput.value = `${year}-${pad2(mm)}-${pad2(dd)}`;

            // Kira umur
            let age = today.getFullYear() - year;
            const belumSambutHariLahir =
                (today.getMonth() < (mm - 1)) ||
                (today.getMonth() === (mm - 1) && today.getDate() < dd);
            if (belumSambutHariLahir) age -= 1;

            ageInput.value = age;

            // Tentukan jantina jika 12 digit
            if (nokp.length >= 12) {
                const lastDigit = parseInt(nokp.charAt(11), 10);
                genderInput.value = (lastDigit % 2 === 1) ? "Lelaki" : "Perempuan";
            } else {
                genderInput.value = "";
            }
        }

        // Fungsi untuk menyimpan temu janji ke localStorage
        function saveAppointment(appointment) {
            // Dapatkan data yang sudah ada
            const appointments = loadAppointments();

            // Tambahkan appointment baru
            appointments.push(appointment);

            // Simpan ke localStorage
            localStorage.setItem('patientAppointments', JSON.stringify(appointments));
        }

        // Fungsi untuk memuat temu janji dari localStorage
        function loadAppointments() {
            const appointmentsJSON = localStorage.getItem('patientAppointments');
            if (appointmentsJSON) {
                return JSON.parse(appointmentsJSON);
            }
            return [];
        }

        // Fungsi untuk menyimpan temu janji
        function simpanTemujanji() {
            const pegawai = document.getElementById('pegawaikes').value;
            const tarikh = document.getElementById('tarikhtemujanji').value;
            const masa = document.getElementById('masatemujanji').value;
            const catatan = document.getElementById('catatantemujanji').value;

            if (!tarikh || !masa) {
                alert("Sila isi tarikh dan masa temu janji!");
                return;
            }

            const startDateTime = tarikh + "T" + masa;
            const appointment = {
                title: `${pegawai || "Temu Janji"} - ${catatan || "Tiada catatan"}`,
                start: startDateTime,
                allDay: false,
                extendedProps: {
                    pegawai: pegawai,
                    catatan: catatan
                }
            };

            // ✅ guna calendar global
            if (calendar) {
                calendar.addEvent(appointment);
            }

            saveAppointment(appointment);

            document.getElementById('pegawaikes').value = '';
            document.getElementById('masatemujanji').value = '';
            document.getElementById('catatantemujanji').value = '';

            const notif = document.getElementById("notif");
            notif.innerText = "Temu janji berjaya disimpan! ✅";
            notif.style.display = "block";
            setTimeout(() => { notif.style.display = "none"; }, 2000);
        }

        function toggleAgamaLain() {
            const select = document.getElementById("agama");
            const input = document.getElementById("agamaLain");
            input.style.display = (select.value === "Lain") ? "block" : "none";
        }

        function toggleBangsaLain() {
            const select = document.getElementById("bangsa");
            const input = document.getElementById("bangsaLain");
            input.style.display = (select.value === "Lain") ? "block" : "none";
        }

        function validateTab(num) {
            let valid = true;
            let inputs = document.querySelectorAll(
                `#compartment${num} input, #compartment${num} textarea, #compartment${num} select`
            );

            // === TAB 2: Tujuan Rujukan ===
            if (num === 2) {
                const bantuanGroup = document.querySelectorAll('#compartment2 .bantuanpraktik');
                const terapiGroup = document.querySelectorAll('#compartment2 .terapisokongan');

                let bantuanChecked = [...bantuanGroup].some(cb => cb.checked);
                let terapiChecked = [...terapiGroup].some(cb => cb.checked);

                if (!bantuanChecked) {
                    valid = false;
                    bantuanGroup[0].closest('.checkbox-group')
                        ?.style.setProperty("border", "2px solid red", "important");
                } else {
                    bantuanGroup[0].closest('.checkbox-group')
                        ?.style.removeProperty("border");
                }

                if (!terapiChecked) {
                    valid = false;
                    terapiGroup[0].closest('.checkbox-group')
                        ?.style.setProperty("border", "2px solid red", "important");
                } else {
                    terapiGroup[0].closest('.checkbox-group')
                        ?.style.removeProperty("border");
                }
            }

            // === TAB 3: Kategori Kes ===
            if (num === 3) {
                const allCheckboxes = document.querySelectorAll('#compartment3 input[type="checkbox"]');
                const lainCheck = document.getElementById("lainCheck");
                const lainInput = document.getElementById("lain");

                let anyChecked = [...allCheckboxes].some(cb => cb.checked);

                if (!anyChecked) {
                    valid = false;
                    document.querySelectorAll('#compartment3 .checkbox-group')
                        .forEach(group => group.style.setProperty("border", "2px solid red", "important"));
                } else {
                    document.querySelectorAll('#compartment3 .checkbox-group')
                        .forEach(group => group.style.removeProperty("border"));
                }

                // Kalau "lain-lain" dipilih tapi text kosong → error
                if (lainCheck && lainCheck.checked) {
                    if (!lainInput.value.trim()) {
                        valid = false;
                        lainInput.classList.add("error");
                        let errorMsg = lainInput.parentNode.querySelector(".error-msg");
                        if (!errorMsg) {
                            errorMsg = document.createElement("div");
                            errorMsg.classList.add("error-msg");
                            errorMsg.style.color = "red";
                            errorMsg.style.fontSize = "12px";
                            errorMsg.style.marginTop = "3px";
                            lainInput.parentNode.appendChild(errorMsg);
                        }
                        errorMsg.innerText = "Sila nyatakan kategori lain-lain.";
                    } else {
                        lainInput.classList.remove("error");
                        let errorMsg = lainInput.parentNode.querySelector(".error-msg");
                        if (errorMsg) errorMsg.remove();
                    }
                }
            }

            // === Input & select biasa (semua tab) ===
            inputs.forEach(input => {
                if (input.type === "checkbox") return; // skip, kita dah handle
                if (input.offsetParent === null) return; // skip hidden

                let errorMsg = input.parentNode.querySelector(".error-msg");

                if (!input.value.trim()) {
                    valid = false;
                    input.classList.add("error");
                    if (!errorMsg) {
                        errorMsg = document.createElement("div");
                        errorMsg.classList.add("error-msg");
                        errorMsg.style.color = "red";
                        errorMsg.style.fontSize = "12px";
                        errorMsg.style.marginTop = "3px";
                        input.parentNode.appendChild(errorMsg);
                    }
                    errorMsg.innerText = "Medan ini wajib diisi.";
                } else {
                    input.classList.remove("error");
                    if (errorMsg) errorMsg.remove();
                }
            });

            return valid;
        }

        function confirmHome(e) {
            e.preventDefault();
            if (confirm("Anda pasti mahu kembali ke Halaman Utama? Maklumat yang belum disimpan mungkin akan hilang.")) {
                window.location.href = e.target.href;
            }
            return false;
        }

        document.addEventListener("DOMContentLoaded", function () {
            // Untuk semua input, textarea & select
            document.querySelectorAll("input, textarea, select").forEach(el => {
                el.addEventListener("input", function () {
                    // Hanya untuk input / textarea
                    if (this.tagName !== "SELECT") {
                        this.classList.remove("error");
                        let errorMsg = this.parentNode.querySelector(".error-msg");
                        if (errorMsg) errorMsg.remove();
                    }
                });

                el.addEventListener("change", function () {
                    // Untuk select dan input date/time
                    if (this.tagName === "SELECT" || this.type === "date" || this.type === "time") {
                        if (this.value && this.value !== "") {
                            this.classList.remove("error");
                            let errorMsg = this.parentNode.querySelector(".error-msg");
                            if (errorMsg) errorMsg.remove();
                        }
                    }
                });
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            // Kalau field IC dah ada value dari request (contoh: dari home page)
            const icInput = document.getElementById("nokp");
            if (icInput.value.trim() !== "") {
                autoTarikhUmur(); // terus auto-kira umur, tarikh lahir & jantina
            }
        });

        document.addEventListener("DOMContentLoaded", function () {
            // Semua input & textarea KECUALI nama
            document.querySelectorAll("input:not(#nama):not([type='date']):not([type='time']), textarea").forEach(el => {
                el.addEventListener("input", function () {
                    this.value = this.value.toUpperCase();
                });
            });
        });


        function validateFinal() {
            // Pastikan semua input di tab 7 valid
            if (!validateTab(6)) {
                switchTab(6); // stay di tab 6
                return false; // ❌ jangan submit
            }

            // Kalau ada tab 7
            if (document.getElementById("compartment7")) {
                if (!validateTab(7)) {
                    switchTab(7); // ❌ stay di tab 7
                    return false;
                }
            }

            return true; // ✅ baru submit ke server
        }

    </script>

</body>

</html>