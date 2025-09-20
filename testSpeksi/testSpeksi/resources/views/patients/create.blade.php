<!DOCTYPE html>
<html lang="ms">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPeKSi - Sistem Maklumat Pesakit</title>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

    <link rel="stylesheet" href="{{ asset('css/createAdminUser.css') }}?v={{ time() }}">
</head>

<body>
    <!-- NOTIFICATION ELEMENT -->
    <div id="notif">Data berjaya disimpan! ✅</div>

    <!-- HEADER SECTION -->
    <div class="header">
        <img src="https://raw.githubusercontent.com/ZulhilmiAmy/Final-Year-Project/main/Image%20banner/Banner-SPeKSi.png"
            alt="Banner">
    </div>

    <!-- BREADCRUMB NAVIGATION -->
    <div class="top-bar">
        <div class="breadcrumbs">
            <a>Login</a> &gt;
            <a href="{{ route('home') }}" onclick="return confirmHome(event)">Halaman Utama</a> &gt;
            <a>Sistem Maklumat Pesakit</a>
        </div>
    </div>

    <!-- MAIN CONTAINER -->
    <div class="container">
        <h1>Sistem Maklumat Pesakit</h1>

        <!-- FLASH MESSAGE JIKA ADA -->
        @if(session('success'))
            <div style="background:#4caf50;color:#fff;padding:8px;border-radius:6px;margin-bottom:10px;">
                {{ session('success') }}
            </div>
        @endif

        <!-- MAIN FORM -->
        <form id="patientForm" method="POST" action="{{ route('patients.store') }}" enctype="multipart/form-data"
            onsubmit="return validateFinal()">
            @csrf

            <!-- TAB NAVIGATION -->
            <div class="tab-container">
                <div class="tab active" data-tab="1">1. Maklumat Peribadi</div>
                <div class="tab disabled" data-tab="2">2. Tujuan Rujukan</div>
                <div class="tab disabled" data-tab="3">3. Kategori Kes</div>
                <div class="tab disabled" data-tab="4">4. Maklumat Perujuk</div>
                <div class="tab disabled" data-tab="5">5. Rujukan Agensi</div>
                <div class="tab disabled" data-tab="6">6. Tarikh Temu Janji</div>
            </div>

            <!-- TAB 1: MAKLUMAT PERIBADI -->
            <div class="compartment active" id="compartment1">
                <h3>Maklumat Peribadi</h3>

                <!-- Maklumat Rujukan -->
                <div class="form-section">
                    <div class="form-section-title">Maklumat Rujukan</div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Tarikh Rujukan Diterima</label>
                                <input type="date" id="tarikhrujukan" name="tarikh_rujukan" required>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Tarikh Tindakbalas Awal</label>
                                <input type="date" id="tindakawal" name="tarikh_tindakbalas_awal">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>No. Fail JKSP</label>
                                <input id="nofile" name="no_fail" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Maklumat Asas Pesakit -->
                <div class="form-section">
                    <div class="form-section-title">Maklumat Asas Pesakit</div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Nama</label>
                                <input id="nama" name="nama" required>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>No. KP</label>
                                <input id="nokp" name="no_kp" maxlength="12" value="{{ request('kp') }}"
                                    placeholder="Cth: 010203101234" oninput="autoTarikhUmur()" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Tarikh Lahir</label>
                                <input type="date" id="tarikhlahir" name="tarikh_lahir" onchange="kiraUmurDariTarikh()">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Umur</label>
                                <input id="umur" name="umur" type="number" onchange="kiraTarikhLahirDariUmur()">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Jantina</label>
                                <select id="jantina" name="jantina">
                                    <option value="" disabled selected hidden>-- Sila Pilih --</option>
                                    <option value="Lelaki">Lelaki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Agama</label>
                                <select id="agama" name="agama" onchange="toggleAgamaLain()" required>
                                    <option value="" disabled selected hidden>-- Sila Pilih --</option>
                                    <option value="Islam">Islam</option>
                                    <option value="Buddha">Buddha</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Kristian">Kristian</option>
                                    <option value="Lain">Lain-lain</option>
                                </select>
                                <input type="text" id="agamaLain" name="agama_lain" placeholder="Sila nyatakan"
                                    style="display:none; margin-top:5px;">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Bangsa</label>
                                <select id="bangsa" name="bangsa" onchange="toggleBangsaLain()" required>
                                    <option value="" disabled selected hidden>-- Sila Pilih --</option>
                                    <option value="Melayu">Melayu</option>
                                    <option value="Cina">Cina</option>
                                    <option value="India">India</option>
                                    <option value="Bumiputera Sabah">Bumiputera Sabah</option>
                                    <option value="Bumiputera Sarawak">Bumiputera Sarawak</option>
                                    <option value="Lain">Lain-lain</option>
                                </select>
                                <input type="text" id="bangsaLain" name="bangsa_lain" placeholder="Sila nyatakan"
                                    style="display:none; margin-top:5px;">
                            </div>
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="form-section-title">Alamat</div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea id="alamat" name="alamat" rows="2" placeholder="Alamat Jalan / Taman / No Rumah"
                            required></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Negeri</label>
                                <select id="negeri" name="negeri" required>
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
                                </select>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Bandar</label>
                                <input id="bandar" name="bandar" required>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Poskod</label>
                                <input id="poskod" maxlength="5" name="poskod" type="number" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>No. Tel.</label>
                                <input id="notel" name="no_tel" type="number">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Maklumat Perubatan -->
                <div class="form-section">
                    <div class="form-section-title">Maklumat Perubatan</div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label>Tarikh Masuk Wad</label>
                                <input type="date" id="tarikhmasuk" name="tarikh_masuk_wad">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label>Tarikh Dijangka Discaj</label>
                                <input type="date" id="tarikhdiscaj" name="tarikh_discaj">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Diagnosa</label>
                        <textarea id="diagnosa" name="diagnosa" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Prognosis</label>
                        <select id="prognosis" name="prognosis">
                            <option value="" disabled selected hidden>--Sila Pilih--</option>
                            <option value="Baik (Good)">Baik (Good)</option>
                            <option value="Sederhana (Fair)">Sederhana (Fair)</option>
                            <option value="Tidak Baik (Poor)">Tidak Baik (Poor)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Mobiliti Pesakit</label>
                        <select id="mobiliti" name="mobiliti">
                            <option value="" disabled selected hidden>-- Sila Pilih --</option>
                            <option value="Berjalan">Berjalan</option>
                            <option value="Kerusi Roda">Kerusi Roda</option>
                            <option value="Crutches">Crutches</option>
                            <option value="Walking Frame">Walking Frame</option>
                            <option value="Bedridden">Bedridden</option>
                        </select>
                    </div>
                </div>

                <!-- Error Message untuk Tab 1 -->
                <div id="tab1-error" class="tab-error">
                    Sila isi semua maklumat yang diperlukan sebelum meneruskan.
                </div>

                <!-- BUTTONS FOR TAB 1 -->
                <div class="button-group">
                    <button type="button" class="save-btn" onclick="simpan(1)">Simpan</button>
                    <button type="button" class="next-btn" onclick="next(1)">Seterusnya</button>
                </div>
            </div>

            <!-- TAB 2: TUJUAN RUJUKAN -->
            <div class="compartment" id="compartment2">
                <h3>Tujuan Rujukan</h3>

                <!-- Bantuan Praktik -->
                <div class="form-section">
                    <div class="form-section-title">BANTUAN PRAKTIK</div>
                    <div class="checkbox-group" id="bantuan-praktik-group">
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

                <!-- Terapi Sokongan -->
                <div class="form-section">
                    <div class="form-section-title">TERAPI SOKONGAN</div>
                    <div class="checkbox-group" id="terapi-sokongan-group">
                        <label><input type="checkbox" class="terapisokongan" name="terapi_sokongan[]"
                                value="Khidmat Perundingan"> Khidmat Perundingan</label>
                        <label><input type="checkbox" class="terapisokongan" name="terapi_sokongan[]"
                                value="Sokongan Emosi"> Sokongan Emosi</label>
                        <label><input type="checkbox" class="terapisokongan" name="terapi_sokongan[]"
                                value="Intervensi Krisis"> Intervensi Krisis</label>
                    </div>
                </div>

                <!-- Error Message untuk Tab 2 -->
                <div id="tab2-error" class="tab-error">
                    Sila pilih sekurang-kurangnya satu pilihan dalam Tujuan Rujukan
                </div>

                <!-- BUTTONS FOR TAB 2 -->
                <div class="button-group">
                    <button type="button" class="back-btn" onclick="back(2)">Kembali</button>
                    <button type="button" class="save-btn" onclick="simpan(2)">Simpan</button>
                    <button type="button" class="next-btn" onclick="next(2)">Seterusnya</button>
                </div>
            </div>

            <!-- TAB 3: KATEGORI KES -->
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

                <!-- Lain-lain Option -->
                <div class="form-group">
                    <label><input type="checkbox" id="lainCheck" onclick="toggleLainTextbox()"> Lain-lain</label>
                    <input id="lain" name="lain_lain" type="text" placeholder="Sila nyatakan"
                        style="display:none; margin-top:5px;">
                </div>

                <!-- Error Message untuk Tab 3 -->
                <div id="tab3-error" class="tab-error">
                    Sila pilih sekurang-kurangnya satu kategori kes
                </div>

                <!-- BUTTONS FOR TAB 3 -->
                <div class="button-group">
                    <button type="button" class="back-btn" onclick="back(3)">Kembali</button>
                    <button type="button" class="save-btn" onclick="simpan(3)">Simpan</button>
                    <button type="button" class="next-btn" onclick="next(3)">Seterusnya</button>
                </div>
            </div>

            <!-- TAB 4: MAKLUMAT PERUJUK -->
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
                                <option value="Perubatan (MED)">Perubatan (MED)</option>
                                <option value="Pembedahan (SURG)">Pembedahan (SURG)</option>
                                <option value="Obstetrik & Ginekologi (O&G)">Obstetrik & Ginekologi (O&G)</option>
                                <option value="Ortopedik (ORTHO)">Ortopedik (ORTHO)</option>
                                <option value="Pediatrik (PAEDS)">Pediatrik (PAEDS)</option>
                                <option value="Oftalmologi (OFTAL)">Oftalmologi (OFTAL)</option>
                                <option value="Telinga, Hidung & Tekak (ENT)">Telinga, Hidung & Tekak (ENT)</option>
                                <option value="Kecemasan (A&E)">Kecemasan (A&E)</option>
                                <option value="Onkologi (ONCHO)">Onkologi (ONCHO)</option>
                                <option value="Psikiatri (PSY)">Psikiatri (PSY)</option>
                                <option value="Rehabilitasi (REHAB)">Rehabilitasi (REHAB)</option>
                                <option value="Nefrologi (NEFRO)">Nefrologi (NEFRO)</option>
                                <option value="Urologi (URO)">Urologi (URO)</option>
                                <option value="Dermatologi (DERMA)">Dermatologi (DERMA)</option>
                                <option value="Neurologi (NEURO)">Neurologi (NEURO)</option>
                                <option value="Pembedahan Neurologi (NEUROS)">Pembedahan Neurologi (NEUROS)</option>
                                <option value="Hematologi (HAEMATO)">Hematologi (HAEMATO)</option>
                                <option value="Kardiologi (CARDIO)">Kardiologi (CARDIO)</option>
                                <option value="Lain-lain (ETC)">Lain-lain (ETC)</option>
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

                <!-- Error Message untuk Tab 4 -->
                <div id="tab4-error" class="tab-error">
                    Sila isi semua maklumat perujuk sebelum meneruskan.
                </div>

                <!-- BUTTONS FOR TAB 4 -->
                <div class="button-group">
                    <button type="button" class="back-btn" onclick="back(4)">Kembali</button>
                    <button type="button" class="save-btn" onclick="simpan(4)">Simpan</button>
                    <button type="button" class="next-btn" onclick="next(4)">Seterusnya</button>
                </div>
            </div>

            <!-- TAB 5: RUJUKAN AGENSI -->
            <div class="compartment" id="compartment5">
                <h3>Rujukan Agensi</h3>

                <!-- Nota untuk user biasa -->
                <div id="user-readonly-note" class="readonly-tab-note" style="display: none;">
                    <strong>Nota:</strong> Anda adalah pengguna biasa. Bahagian ini hanya untuk dilihat sahaja dan tidak
                    boleh diisi.
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label>Nama Agensi</label>
                            <input id="agensi" name="agensi" class="user-restricted-field">
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label>Nama Pembekal</label>
                            <input id="pembekal" name="pembekal" class="user-restricted-field">
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label>Tarikh Laporan Dihantar</label>
                            <input type="date" id="tarikhlaporan" name="tarikh_laporan" class="user-restricted-field">
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label>Tarikh Dokumen Lengkap Diterima</label>
                            <input type="date" id="tarikhdoklengkap" name="tarikh_dokumen_lengkap"
                                class="user-restricted-field">
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label>Item Dipohon</label>
                            <input id="item" name="item_dipohon" class="user-restricted-field">
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label>Tarikh Kelulusan</label>
                            <input type="date" id="tarikhlulus" name="tarikh_kelulusan" class="user-restricted-field">
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label>Tanggungan Pesakit (RM)</label>
                            <input type="number" id="tanggung" name="tanggungan" step="0.01"
                                class="user-restricted-field">
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label>Jumlah Dipohon (RM)</label>
                            <input type="number" id="dipohon" name="jumlah_dipohon" step="0.01"
                                class="user-restricted-field">
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label>Jumlah Kelulusan (RM)</label>
                            <input type="number" id="lulus" name="jumlah_kelulusan" step="0.01"
                                class="user-restricted-field">
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label>Tarikh Tuntutan</label>
                            <input type="date" id="tarikhtuntut" name="tarikh_tuntut"
                                class="small-date-input user-restricted-field">
                        </div>
                    </div>
                </div>

                <!-- Upload Dokumen -->
                <div class="form-section">
                    <div class="form-section-title">Muat Naik Dokumen</div>
                    <div class="file-upload">
                        <label class="file-upload-label">Dokumen Sokongan</label>
                        <input type="file" id="dokumen" name="dokumen_sokongan" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                            class="user-restricted-field">
                        <small>Format yang diterima: PDF, DOC, DOCX, JPG, JPEG, PNG</small>
                    </div>
                </div>

                <!-- BUTTONS FOR TAB 5 -->
                <div class="button-group">
                    <button type="button" class="back-btn" onclick="back(5)">Kembali</button>
                    <!-- Sembunyikan butang Simpan untuk user biasa -->
                    <button type="button" class="save-btn user-restricted-btn" onclick="simpan(5)">Simpan</button>
                    <button type="button" class="next-btn" onclick="next(5)">Seterusnya</button>
                </div>
            </div>

            <!-- TAB 6: TARIKH TEMU JANJI -->
            <div class="compartment" id="compartment6">
                <h3>Tarikh Temu Janji</h3>

                <!-- Calendar Display -->
                <div id="calendar-container">
                    <div id="calendarTab6"></div>
                </div>

                <!-- Form Inputs for Appointment -->
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

                <!-- Error Message untuk Tab 6 -->
                <div id="tab6-error" class="tab-error">
                    Sila isi semua maklumat temu janji sebelum meneruskan.
                </div>

                <!-- BUTTONS FOR TAB 6 -->
                <div class="button-group">
                    <button type="button" class="back-btn" onclick="back(6)">Kembali</button>
                    <button type="button" class="save-btn" onclick="simpanTemujanji()">Simpan Temu Janji</button>
                    <button type="submit" class="next-btn">Hantar Semua</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        // // GLOBAL VARIABLES
        // let data = {};
        // let calendar;
        // let isRegularUser = @json(auth()->user()->role === 'user'); // Tetapkan ini berdasarkan jenis user sebenarnya

        // // INITIALIZE PAGE ON LOAD
        // document.addEventListener('DOMContentLoaded', function () {
        //     // Tentukan jenis user (contoh: dari session atau role)
        //     // Dalam implementasi sebenar, ini boleh datang dari server
        //     // isRegularUser = {{-- auth()->user()->role === 'user' --}} true;

        //     // Jika user biasa, hadkan akses di Tab 5
        //     if (isRegularUser) {
        //         restrictUserAccess();
        //     }

        //     const calendarEl = document.getElementById('calendarTab6');
        //     if (calendarEl) {
        //         calendar = new FullCalendar.Calendar(calendarEl, {
        //             initialView: '',
        //             height: 'auto',
        //             locale: 'ms',
        //             headerToolbar: {
        //                 left: 'prev,next,today',
        //                 center: 'title',
        //                 right: ''
        //             },
        //             buttonText: {
        //                 today: 'Hari Ini',
        //             },
        //             events: @json($events),
        //             eventClick: function (info) {
        //                 alert(
        //                     "Pemohon: " + info.event.title +
        //                     "\nPegawai Kes: " + info.event.extendedProps.pegawai +
        //                     "\nNo. KP: " + info.event.extendedProps.no_kp +
        //                     "\nMasa: " + info.event.start.toLocaleString('ms-MY')
        //                 );
        //             }
        //         });
        //         calendar.render();
        //     }

        //     // Add event listeners for Tab 2 checkboxes
        //     document.querySelectorAll('.bantuanpraktik, .terapisokongan').forEach(checkbox => {
        //         checkbox.addEventListener('change', function () {
        //             validateTab2();
        //         });
        //     });

        //     // Auto-generate No. Fail JKSP on page load
        //     let autoId = "JKSP-" + Math.floor(Math.random() * 100000);
        //     document.getElementById("nofile").value = autoId;

        //     // If IC field has value from request (e.g., from home page)
        //     const icInput = document.getElementById("nokp");
        //     if (icInput.value.trim() !== "") {
        //         autoTarikhUmur(); // Auto-calculate age, birth date & gender
        //     }

        //     // Add input listeners for error removal
        //     document.querySelectorAll("input, textarea, select").forEach(el => {
        //         el.addEventListener("input", function () {
        //             if (this.tagName !== "SELECT") {
        //                 this.classList.remove("error");
        //                 let errorMsg = this.parentNode.querySelector(".error-msg");
        //                 if (errorMsg) errorMsg.remove();
        //             }
        //         });

        //         el.addEventListener("change", function () {
        //             if (this.tagName === "SELECT" || this.type === "date" || this.type === "time") {
        //                 if (this.value && this.value !== "") {
        //                     this.classList.remove("error");
        //                     let errorMsg = this.parentNode.querySelector(".error-msg");
        //                     if (errorMsg) errorMsg.remove();
        //                 }
        //             }
        //         });
        //     });

        //     // Uppercase conversion for all inputs except name, date, and time
        //     document.querySelectorAll("input:not(#nama):not([type='date']):not([type='time']), textarea").forEach(el => {
        //         el.addEventListener("input", function () {
        //             this.value = this.value.toUpperCase();
        //         });
        //     });
        // });

        // // FUNCTION TO RESTRICT ACCESS FOR REGULAR USERS
        // function restrictUserAccess() {
        //     // Tampilkan nota
        //     document.getElementById('user-readonly-note').style.display = 'block';

        //     // Buat semua field di Tab 5 menjadi readonly
        //     const restrictedFields = document.querySelectorAll('.user-restricted-field');
        //     restrictedFields.forEach(field => {
        //         field.setAttribute('readonly', true);
        //         field.classList.add('readonly-field');

        //         // Untuk elemen file, kita perlu disable
        //         if (field.type === 'file') {
        //             field.setAttribute('disabled', true);
        //         }
        //     });

        //     // Sembunyikan butang Simpan untuk Tab 5
        //     const saveButtons = document.querySelectorAll('.user-restricted-btn');
        //     saveButtons.forEach(btn => {
        //         btn.style.display = 'none';
        //     });
        // }

        // // FUNCTION TO CALCULATE AGE, BIRTH DATE & GENDER FROM IC NUMBER
        // function autoTarikhUmur() {
        //     const icInput = document.getElementById("nokp");
        //     const dobInput = document.getElementById("tarikhlahir");
        //     const ageInput = document.getElementById("umur");
        //     const genderInput = document.getElementById("jantina");

        //     // Accept numbers with/without dashes, take only digits
        //     const nokp = (icInput.value || "").replace(/\D/g, "");

        //     // If less than 6 digits (YYMMDD) - clear fields
        //     if (nokp.length < 6) {
        //         return;
        //     }

        //     const yy = parseInt(nokp.slice(0, 2), 10);
        //     const mm = parseInt(nokp.slice(2, 4), 10);
        //     const dd = parseInt(nokp.slice(4, 6), 10);

        //     // Basic validation for month & day
        //     if (mm < 1 || mm > 12 || dd < 1 || dd > 31) {
        //         return;
        //     }

        //     const today = new Date();
        //     const century = (yy <= (today.getFullYear() % 100)) ? 2000 : 1900;
        //     const year = century + yy;

        //     // Build date and validate (avoid 31/02 etc.)
        //     const candidate = new Date(year, mm - 1, dd);
        //     if (
        //         candidate.getFullYear() !== year ||
        //         candidate.getMonth() !== (mm - 1) ||
        //         candidate.getDate() !== dd
        //     ) {
        //         return;
        //     }

        //     // Format for <input type="date"> (YYYY-MM-DD)
        //     const pad2 = n => String(n).padStart(2, "0");
        //     dobInput.value = `${year}-${pad2(mm)}-${pad2(dd)}`;

        //     // Calculate age
        //     let age = today.getFullYear() - year;
        //     const belumSambutHariLahir =
        //         (today.getMonth() < (mm - 1)) ||
        //         (today.getMonth() === (mm - 1) && today.getDate() < dd);
        //     if (belumSambutHariLahir) age -= 1;

        //     ageInput.value = age;

        //     // Determine gender if 12 digits
        //     if (nokp.length >= 12) {
        //         const lastDigit = parseInt(nokp.charAt(11), 10);
        //         genderInput.value = (lastDigit % 2 === 1) ? "Lelaki" : "Perempuan";
        //     }
        // }

        // // FUNCTION TO TOGGLE ADDITIONAL TEXT FIELD FOR "OTHER" OPTIONS
        // function toggleAgamaLain() {
        //     const select = document.getElementById("agama");
        //     const input = document.getElementById("agamaLain");
        //     input.style.display = (select.value === "Lain") ? "block" : "none";
        // }

        // function toggleBangsaLain() {
        //     const select = document.getElementById("bangsa");
        //     const input = document.getElementById("bangsaLain");
        //     input.style.display = (select.value === "Lain") ? "block" : "none";
        // }

        // function toggleLainTextbox() {
        //     const lainCheckbox = document.getElementById('lainCheck');
        //     const lainTextbox = document.getElementById('lain');
        //     lainTextbox.style.display = lainCheckbox.checked ? 'block' : 'none';
        // }

        // // FUNCTION TO SWITCH BETWEEN TABS
        // function switchTab(num) {
        //     document.querySelectorAll('.compartment').forEach(c => c.classList.remove('active'));
        //     document.querySelector(`#compartment${num}`).classList.add('active');
        //     document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        //     document.querySelector(`.tab[data-tab="${num}"]`).classList.add('active');
        //     document.querySelector(`.tab[data-tab="${num}"]`).classList.remove('disabled');

        //     // Redraw calendar when opening Tab 6
        //     if (num === 6 && calendar) {
        //         setTimeout(() => {
        //             calendar.updateSize();
        //         }, 200);
        //     }
        // }

        // // FUNCTION TO NAVIGATE TO NEXT TAB
        // function next(num) {
        //     // Special validation for each tab
        //     let isValid = true;

        //     if (num === 1) {
        //         isValid = validateTab1();
        //     } else if (num === 2) {
        //         isValid = validateTab2();
        //     } else if (num === 3) {
        //         isValid = validateTab3();
        //     } else if (num === 4) {
        //         isValid = validateTab4();
        //     } else if (num === 5) {
        //         isValid = validateTab5();
        //     } else if (num === 6) {
        //         isValid = validateTab6();
        //     }

        //     if (!isValid) return false;

        //     simpan(num, true);
        //     document.querySelector(`.tab[data-tab="${num + 1}"]`).classList.remove('disabled');
        //     switchTab(num + 1);
        // }

        // // FUNCTION TO NAVIGATE TO PREVIOUS TAB
        // function back(num) {
        //     simpan(num, false); // Save but don't show notification
        //     switchTab(num - 1);
        // }

        // // FUNCTION TO VALIDATE TAB 1 (MAKLUMAT PERIBADI)
        // function validateTab1() {
        //     const requiredFields = [
        //         'tarikhrujukan', 'nama', 'nokp', 'agama', 'bangsa',
        //         'alamat', 'negeri', 'bandar', 'poskod'
        //     ];

        //     let isValid = true;

        //     // Validate all required fields
        //     requiredFields.forEach(fieldId => {
        //         const field = document.getElementById(fieldId);
        //         if (field && !field.value.trim()) {
        //             field.classList.add('error');
        //             isValid = false;
        //         } else if (field) {
        //             field.classList.remove('error');
        //         }
        //     });

        //     // Validate IC number format
        //     const icField = document.getElementById('nokp');
        //     if (icField && icField.value.trim()) {
        //         const icValue = icField.value.replace(/\D/g, '');
        //         if (icValue.length < 6) {
        //             icField.classList.add('error');
        //             isValid = false;
        //         }
        //     }

        //     // Validate birth date
        //     const dobField = document.getElementById('tarikhlahir');
        //     if (dobField && !dobField.value.trim()) {
        //         dobField.classList.add('error');
        //         isValid = false;
        //     }

        //     // Validate age
        //     const ageField = document.getElementById('umur');
        //     if (ageField && (!ageField.value.trim() || isNaN(ageField.value) || ageField.value < 0)) {
        //         ageField.classList.add('error');
        //         isValid = false;
        //     }

        //     // Validate gender
        //     const genderField = document.getElementById('jantina');
        //     if (genderField && !genderField.value) {
        //         genderField.classList.add('error');
        //         isValid = false;
        //     }

        //     // Show/hide error message
        //     const errorDiv = document.getElementById('tab1-error');
        //     if (errorDiv) {
        //         if (!isValid) {
        //             errorDiv.style.display = 'block';
        //         } else {
        //             errorDiv.style.display = 'none';
        //         }
        //     }

        //     return isValid;
        // }

        // // FUNCTION TO VALIDATE TAB 2 (TUJUAN RUJUKAN)
        // function validateTab2() {
        //     const bantuanCheckboxes = document.querySelectorAll('.bantuanpraktik:checked');
        //     const terapiCheckboxes = document.querySelectorAll('.terapisokongan:checked');
        //     const errorMsg = document.getElementById('tab2-error');

        //     // Check if at least one checkbox is selected
        //     const isValid = bantuanCheckboxes.length > 0 || terapiCheckboxes.length > 0;

        //     if (!isValid) {
        //         // Mark checkbox groups with red border
        //         document.getElementById('bantuan-praktik-group').classList.add('error-border');
        //         document.getElementById('terapi-sokongan-group').classList.add('error-border');
        //         if (errorMsg) errorMsg.style.display = 'block';
        //         return false;
        //     }

        //     // Remove error indicators if valid
        //     document.getElementById('bantuan-praktik-group').classList.remove('error-border');
        //     document.getElementById('terapi-sokongan-group').classList.remove('error-border');
        //     if (errorMsg) errorMsg.style.display = 'none';

        //     return true;
        // }

        // // FUNCTION TO VALIDATE TAB 3 (KATEGORI KES)
        // function validateTab3() {
        //     const allCheckboxes = document.querySelectorAll('#compartment3 input[type="checkbox"]');
        //     const lainCheck = document.getElementById("lainCheck");
        //     const lainInput = document.getElementById("lain");
        //     const errorMsg = document.getElementById('tab3-error');

        //     let anyChecked = [...allCheckboxes].some(cb => cb.checked);
        //     let isValid = true;

        //     if (!anyChecked) {
        //         isValid = false;
        //         document.querySelectorAll('#compartment3 .checkbox-group')
        //             .forEach(group => group.classList.add('error-border'));
        //     } else {
        //         document.querySelectorAll('#compartment3 .checkbox-group')
        //             .forEach(group => group.classList.remove('error-border'));
        //     }

        //     // If "other" is checked but text is empty → error
        //     if (lainCheck && lainCheck.checked) {
        //         if (!lainInput.value.trim()) {
        //             isValid = false;
        //             lainInput.classList.add("error");
        //             let errorMsg = lainInput.parentNode.querySelector(".error-msg");
        //             if (!errorMsg) {
        //                 errorMsg = document.createElement("div");
        //                 errorMsg.classList.add("error-msg");
        //                 errorMsg.style.color = "red";
        //                 errorMsg.style.fontSize = "12px";
        //                 errorMsg.style.marginTop = "3px";
        //                 lainInput.parentNode.appendChild(errorMsg);
        //             }
        //             errorMsg.innerText = "Sila nyatakan kategori lain-lain.";
        //         } else {
        //             lainInput.classList.remove("error");
        //             let errorMsg = lainInput.parentNode.querySelector(".error-msg");
        //             if (errorMsg) errorMsg.remove();
        //         }
        //     }

        //     // Show/hide error message
        //     if (errorMsg) {
        //         if (!isValid) {
        //             errorMsg.style.display = 'block';
        //         } else {
        //             errorMsg.style.display = 'none';
        //         }
        //     }

        //     return isValid;
        // }

        // // FUNCTION TO VALIDATE TAB 4 (MAKLUMAT PERUJUK)
        // function validateTab4() {
        //     const namaPerujuk = document.getElementById('namaperujuk').value.trim();
        //     const disiplin = document.getElementById('disiplin').value;
        //     const wadRujuk = document.getElementById('wadrujuk').value;
        //     const diagnosisRujuk = document.getElementById('diagnosisrujuk').value.trim();
        //     const errorMsg = document.getElementById('tab4-error');

        //     let isValid = true;

        //     // Check each required field
        //     if (!namaPerujuk) {
        //         document.getElementById('namaperujuk').classList.add('error');
        //         isValid = false;
        //     } else {
        //         document.getElementById('namaperujuk').classList.remove('error');
        //     }

        //     if (!disiplin) {
        //         document.getElementById('disiplin').classList.add('error');
        //         isValid = false;
        //     } else {
        //         document.getElementById('disiplin').classList.remove('error');
        //     }

        //     if (!wadRujuk) {
        //         document.getElementById('wadrujuk').classList.add('error');
        //         isValid = false;
        //     } else {
        //         document.getElementById('wadrujuk').classList.remove('error');
        //     }

        //     if (!diagnosisRujuk) {
        //         document.getElementById('diagnosisrujuk').classList.add('error');
        //         isValid = false;
        //     } else {
        //         document.getElementById('diagnosisrujuk').classList.remove('error');
        //     }

        //     // Show/hide error message
        //     if (errorMsg) {
        //         if (!isValid) {
        //             errorMsg.style.display = 'block';
        //         } else {
        //             errorMsg.style.display = 'none';
        //         }
        //     }

        //     return isValid;
        // }

        // // FUNCTION TO VALIDATE TAB 5 (RUJUKAN AGENSI)
        // function validateTab5() {
        //     // Untuk user biasa, skip validation kerana mereka tidak boleh mengisi
        //     if (isRegularUser) {
        //         return true;
        //     }

        //     // Untuk admin/privileged user, lakukan validation seperti biasa
        //     // Tab 5 doesn't have required fields, so always return true
        //     return true;
        // }

        // // FUNCTION TO VALIDATE TAB 6 (TARIKH TEMU JANJI)
        // function validateTab6() {
        //     const pegawai = document.getElementById('pegawaikes').value.trim();
        //     const tarikh = document.getElementById('tarikhtemujanji').value;
        //     const masa = document.getElementById('masatemujanji').value;
        //     const errorMsg = document.getElementById('tab6-error');
        //     let isValid = true;

        //     // Check each required field
        //     if (!pegawai) {
        //         document.getElementById('pegawaikes').classList.add('error');
        //         isValid = false;
        //     } else {
        //         document.getElementById('pegawaikes').classList.remove('error');
        //     }

        //     if (!tarikh) {
        //         document.getElementById('tarikhtemujanji').classList.add('error');
        //         isValid = false;
        //     } else {
        //         document.getElementById('tarikhtemujanji').classList.remove('error');
        //     }

        //     if (!masa) {
        //         document.getElementById('masatemujanji').classList.add('error');
        //         isValid = false;
        //     } else {
        //         document.getElementById('masatemujanji').classList.remove('error');
        //     }

        //     // Show/hide error message
        //     if (errorMsg) {
        //         if (!isValid) {
        //             errorMsg.style.display = 'block';
        //         } else {
        //             errorMsg.style.display = 'none';
        //         }
        //     }

        //     return isValid;
        // }

        // // FUNCTION TO VALIDATE CURRENT TAB
        // function validateTab(num) {
        //     if (num === 1) return validateTab1();
        //     if (num === 2) return validateTab2();
        //     if (num === 3) return validateTab3();
        //     if (num === 4) return validateTab4();
        //     if (num === 5) return validateTab5();
        //     if (num === 6) return validateTab6();
        //     return true;
        // }

        // // FUNCTION TO SAVE CURRENT TAB DATA
        // function simpan(num, showNotif = true) {
        //     // Untuk user biasa, skip saving untuk Tab 5
        //     if (isRegularUser && num === 5) {
        //         if (showNotif) {
        //             const notif = document.getElementById("notif");
        //             notif.style.display = "block";
        //             setTimeout(() => {
        //                 notif.style.display = "none";
        //                 notif.style.background = "#4CAF50";
        //             }, 2000);
        //         }
        //         return false;
        //     }

        //     if (!validateTab(num)) return false;

        //     // If all valid → save data
        //     let inputs = document.querySelectorAll(`#compartment${num} input, #compartment${num} textarea, #compartment${num} select`);
        //     inputs.forEach(i => {
        //         if (i.type === "checkbox") {
        //             data[i.id || i.className] = i.checked ? "Ya" : "Tidak";
        //         } else {
        //             data[i.id] = i.value;
        //         }
        //     });

        //     if (showNotif) {
        //         const notif = document.getElementById("notif");
        //         notif.innerText = `Bahagian ${num} berjaya disimpan! ✅`;
        //         notif.style.display = "block";
        //         setTimeout(() => {
        //             notif.style.display = "none";
        //         }, 2000);
        //     }

        //     return true;
        // }

        // // FUNCTION TO SAVE APPOINTMENT TO LOCALSTORAGE
        // function saveAppointment(appointment) {
        //     const appointments = loadAppointments();
        //     appointments.push(appointment);
        //     localStorage.setItem('patientAppointments', JSON.stringify(appointments));
        // }

        // // FUNCTION TO LOAD APPOINTMENTS FROM LOCALSTORAGE
        // function loadAppointments() {
        //     const appointmentsJSON = localStorage.getItem('patientAppointments');
        //     if (appointmentsJSON) {
        //         return JSON.parse(appointmentsJSON);
        //     }
        //     return [];
        // }

        // // FUNCTION TO SAVE APPOINTMENT (TAB 6)
        // function simpanTemujanji() {
        //     if (!validateTab6()) {
        //         return false;
        //     }

        //     const pegawai = document.getElementById('pegawaikes').value;
        //     const tarikh = document.getElementById('tarikhtemujanji').value;
        //     const masa = document.getElementById('masatemujanji').value;
        //     const catatan = document.getElementById('catatantemujanji').value;

        //     const startDateTime = tarikh + "T" + masa;
        //     const appointment = {
        //         title: `${pegawai || "Temu Janji"} - ${catatan || "Tiada catatan"}`,
        //         start: startDateTime,
        //         allDay: false,
        //         extendedProps: {
        //             pegawai: pegawai,
        //             catatan: catatan
        //         }
        //     };

        //     // Add to calendar if available
        //     if (calendar) {
        //         calendar.addEvent(appointment);
        //     }

        //     saveAppointment(appointment);

        //     // Clear form fields
        //     document.getElementById('pegawaikes').value = '';
        //     document.getElementById('tarikhtemujanji').value = '';
        //     document.getElementById('masatemujanji').value = '';
        //     document.getElementById('catatantemujanji').value = '';

        //     // Show success notification
        //     const notif = document.getElementById("notif");
        //     notif.innerText = "Temu janji berjaya disimpan! ✅";
        //     notif.style.display = "block";
        //     setTimeout(() => { notif.style.display = "none"; }, 2000);

        //     return true;
        // }

        // // FUNCTION TO VALIDATE FINAL FORM SUBMISSION
        // function validateFinal() {
        //     // Ensure all inputs in tab 6 are valid
        //     if (!validateTab6()) {
        //         switchTab(6); // Stay on tab 6
        //         return false; // Don't submit
        //     }

        //     return true; // Submit to server
        // }

        // // FUNCTION TO CONFIRM NAVIGATION TO HOME PAGE
        // function confirmHome(e) {
        //     e.preventDefault();
        //     if (confirm("Anda pasti mahu kembali ke Halaman Utama? Maklumat yang belum disimpan mungkin akan hilang.")) {
        //         window.location.href = e.target.href;
        //     }
        //     return false;
        // }

        // // FUNCTION TO CONFIRM NAVIGATION TO LOGIN PAGE
        // function confirmGoLogin() {
        //     if (confirm("Adakah anda pasti mahu kembali ke halaman Login?")) {
        //         window.location.href = "{{ route('login.custom') }}";
        //     }
        // }

        // // ADD CLICK LISTENERS TO TABS
        // document.querySelectorAll('.tab').forEach(tab => {
        //     tab.addEventListener('click', function () {
        //         let tabNum = parseInt(this.dataset.tab);
        //         if (!this.classList.contains('disabled')) {
        //             switchTab(tabNum);
        //         }
        //     });
        // });

        // // FUNCTION TO CALCULATE AGE FROM BIRTH DATE
        // function kiraUmurDariTarikh() {
        //     const dobInput = document.getElementById("tarikhlahir");
        //     const ageInput = document.getElementById("umur");

        //     if (!dobInput.value) return;

        //     const birthDate = new Date(dobInput.value);
        //     const today = new Date();

        //     let age = today.getFullYear() - birthDate.getFullYear();
        //     const monthDiff = today.getMonth() - birthDate.getMonth();

        //     if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        //         age--;
        //     }

        //     ageInput.value = age;
        // }

        // // FUNCTION TO CALCULATE BIRTH DATE FROM AGE
        // function kiraTarikhLahirDariUmur() {
        //     const ageInput = document.getElementById("umur");
        //     const dobInput = document.getElementById("tarikhlahir");

        //     if (!ageInput.value) return;

        //     const today = new Date();
        //     const birthYear = today.getFullYear() - parseInt(ageInput.value);

        //     // Set to January 1st of the calculated year
        //     const calculatedDate = new Date(birthYear, 0, 1);

        //     // Format for <input type="date"> (YYYY-MM-DD)
        //     const pad2 = n => String(n).padStart(2, "0");
        //     dobInput.value = `${calculatedDate.getFullYear()}-${pad2(calculatedDate.getMonth() + 1)}-${pad2(calculatedDate.getDate())}`;
        // }
    </script>
</body>

</html>