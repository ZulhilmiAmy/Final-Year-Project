{{-- resources/views/patients/show.blade.php --}}
<!doctype html>
<html lang="ms">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Butiran Pesakit — {{ $patient->nama ?? '-' }}</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #eef2f7;
      margin: 0;
      padding: 20px;
    }

    .header {
      color: #fff;
      padding: 0;
      text-align: center;
      margin: -20px -20px 20px -20px;
    }

    .header img {
      width: 100%;
      max-height: 180px;
      object-fit: cover;
    }

    .top-bar {
      display: flex;
      background: #f8f9fa;
      padding: 8px 20px;
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

    .breadcrumbs .separator {
      color: #204d84;
      margin: 0 4px;
    }

    .container {
      max-width: 1000px;
      margin: 20px auto;
      padding: 25px;
      background: #fdfdfd;
      border-radius: 12px;
      border-top: 6px solid #204d84;
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
    }

    h1 {
      color: #204d84;
      margin: 0 0 20px 0;
      text-align: center;
      font-size: 26px;
    }

    .section-card {
      margin-bottom: 25px;
      padding: 18px 20px;
      border: 1px solid #e0e6ed;
      border-radius: 10px;
      background: #fafcff;
    }

    .section-card h2 {
      margin-top: 0;
      margin-bottom: 15px;
      font-size: 18px;
      color: #204d84;
      border-left: 5px solid #50a7c2;
      padding-left: 10px;
    }

    .form-group {
      margin: 12px 0;
    }

    label {
      font-weight: bold;
      color: #333;
      display: block;
      margin-bottom: 4px;
    }

    .static-field {
      display: inline-block;
      background: #f5f8fb;
      border: 1px dashed #ccc;
      border-radius: 4px;
      padding: 6px 10px;
      min-width: 200px;
    }

    .wide-field {
      min-width: 350px;
    }

    .action-buttons {
      display: flex;
      gap: 10px;
      justify-content: flex-end;
      margin-top: 25px;
    }

    .action-button {
      padding: 8px 14px;
      border-radius: 6px;
      font-size: 14px;
      cursor: pointer;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: background 0.2s ease;
      color: #fff;
    }

    .action-button.back {
      background: #6c757d;
    }

    .action-button.edit {
      background: #2196f3;
    }

    .action-button.print {
      background: #ff9800;
    }

    .action-button:hover {
      opacity: 0.9;
    }
  </style>
</head>

<body>
  <div class="header">
    <img src="https://raw.githubusercontent.com/ZulhilmiAmy/Final-Year-Project/main/Image%20banner/Banner-SPeKSi.png"
      alt="Banner">

    <div class="top-bar">
      <div class="breadcrumbs">
        <a href="{{ route('login.custom') }}" onclick="return confirmNav(event, 'Pergi ke halaman Log Masuk?')">Log
          Masuk</a>
        <span class="separator">&gt;</span>
        <a href="{{ route('home') }}" onclick="return confirmNav(event, 'Kembali ke Halaman Utama?')">Halaman Utama</a>
        <span class="separator">&gt;</span>
        <a href="{{ route('patients.history', ['no_kp' => $patient->no_kp]) }}"
          onclick="return confirmNav(event, 'Lihat Sejarah Pesakit?')">Sejarah Pesakit</a>
        <span class="separator">&gt;</span>
        <a>Butiran Pesakit</a>
      </div>
    </div>
  </div>

  <div class="container">
    <h1>Butiran Pesakit</h1>

    @php
      use Carbon\Carbon;
      function fmtDate($d)
      {
        if (!$d)
          return '-';
        try {
          return Carbon::parse($d)->format('d-m-Y');
        } catch (\Exception $e) {
          return $d;
        }
      }
      function showGroup($val)
      {
        if (!$val)
          return '-';
        if (is_string($val) && (substr($val, 0, 1) === '[' || substr($val, 0, 1) === '{')) {
          $arr = json_decode($val, true);
          if (is_array($arr))
            return implode(', ', $arr);
        }
        if (is_array($val))
          return implode(', ', $val);
        return (string) $val;
      }
    @endphp

    <div class="section-card">
      <h2>1. Maklumat Peribadi</h2>
      <div class="form-group"><label>Nama</label><span class="static-field">{{ $patient->nama ?? '-' }}</span></div>
      <div class="form-group"><label>No. KP</label><span class="static-field">{{ $patient->no_kp ?? '-' }}</span></div>
      <div class="form-group"><label>Tarikh Rujukan</label><span
          class="static-field">{{ fmtDate($patient->tarikh_rujukan) }}</span></div>
      <div class="form-group"><label>Alamat</label><span
          class="static-field wide-field">{{ $patient->alamat ?? '-' }}</span></div>
      <div class="form-group"><label>No. Tel.</label><span class="static-field">{{ $patient->no_tel ?? '-' }}</span>
      </div>
      <div class="form-group"><label>Tarikh Lahir</label><span
          class="static-field">{{ fmtDate($patient->tarikh_lahir) }}</span></div>
      <div class="form-group"><label>Umur</label><span class="static-field">{{ $patient->umur ?? '-' }}</span></div>
      <div class="form-group"><label>Jantina</label><span class="static-field">{{ $patient->jantina ?? '-' }}</span>
      </div>
    </div>

    <div class="section-card">
      <h2>2. Tujuan Rujukan</h2>
      <div class="form-group"><label>Bantuan Praktik</label><span
          class="static-field">{{ showGroup($patient->bantuan_praktik) }}</span></div>
      <div class="form-group"><label>Terapi Sokongan</label><span
          class="static-field">{{ showGroup($patient->terapi_sokongan) }}</span></div>
    </div>

    <div class="section-card">
      <h2>3. Kategori Kes</h2>
      <div class="form-group"><label>Kategori</label><span
          class="static-field">{{ showGroup($patient->kategori_kes) }}</span></div>
    </div>

    <div class="section-card">
      <h2>4. Maklumat Perujuk</h2>
      <div class="form-group"><label>Nama Perujuk</label><span
          class="static-field">{{ $patient->nama_perujuk ?? '-' }}</span></div>
      <div class="form-group"><label>Disiplin</label><span class="static-field">{{ $patient->disiplin ?? '-' }}</span>
      </div>
      <div class="form-group"><label>Wad / Klinik</label><span
          class="static-field">{{ $patient->wad_rujuk ?? '-' }}</span></div>
      <div class="form-group"><label>Diagnosis Rujuk</label><span
          class="static-field">{{ $patient->diagnosis_rujuk ?? '-' }}</span></div>
    </div>

    <div class="section-card">
      <h2>6. Rujukan ke Agensi</h2>
      <div class="form-group"><label>Nama Agensi</label><span class="static-field">{{ $patient->agensi ?? '-' }}</span></div>
      <div class="form-group"><label>Nama Pembekal</label><span class="static-field">{{ $patient->pembekal ?? '-' }}</span></div>      
      <div class="form-group"><label>Tarikh Laporan Dihantar</label><span
          class="static-field">{{ fmtDate($patient->tarikh_laporan) }}</span></div>
      <div class="form-group"><label>Tarikh Dokumen Lengkap Diterima</label><span
          class="static-field">{{ fmtDate($patient->tarikh_dokumen_lengkap) }}</span></div>
      <div class="form-group"><label>Item Dipohon</label><span
          class="static-field">{{ $patient->item_dipohon ?? '-' }}</span></div>
      <div class="form-group"><label>Tarikh Kelulusan</label><span
          class="static-field">{{ fmtDate($patient->tarikh_kelulusan) }}</span></div>
      <div class="form-group"><label>Tanggungan Pesakit (RM)</label><span class="static-field">{{ $patient->tanggungan ?? '-' }}</span></div>
      <div class="form-group"><label>Jumlah Dipohon (RM)</label><span class="static-field">{{ $patient->jumlah_dipohon ?? '-' }}</span></div>
      <div class="form-group"><label>Jumlah Kelulusan (RM)</label><span class="static-field">{{ $patient->jumlah_kelulusan ?? '-' }}</span></div>
      <div class="form-group"><label>Tarikh Tuntutan</label><span
          class="static-field">{{ fmtDate($patient->tarikh_tuntut) }}</span></div>
      
    </div>

    <div class="section-card">
      <h2>7. Temu Janji</h2>
      <div class="form-group"><label>Pegawai Kes</label><span
          class="static-field">{{ $patient->pegawai_kes ?? '-' }}</span></div>
      <div class="form-group"><label>Tarikh Temu Janji</label><span
          class="static-field">{{ fmtDate($patient->tarikh_temu) }}</span></div>
      <div class="form-group"><label>Masa Temu Janji</label><span 
          class="static-field">{{ $patient->masa_temu ? \Carbon\Carbon::parse($patient->masa_temu)->format('h:i A') : '-' }}</span></div>
      <div class="form-group"><label>Catatan Temu Janji</label><span
          class="static-field wide-field">{{ $patient->catatan_temu ?? '-' }}</span></div>
    </div>

    <div class="action-buttons">
      <a class="action-button back" href="{{ route('patients.history', ['no_kp' => $patient->no_kp]) }}"
        <i class="fas fa-arrow-left"></i> Kembali
      </a>
      <a class="action-button edit" href="{{ route('patients.edit', $patient->id) }}"
        onclick="return confirm('Anda pasti mahu mengedit maklumat pesakit ini?')">
        <i class="fas fa-edit"></i> Edit
      </a>
      <a class="action-button print" href="{{ route('patients.letter', $patient->id) }}" target="_blank"
        <i class="fas fa-print"></i> Cetak Surat
      </a>
    </div>
  </div>
</body>
<script>
  function confirmNav(e, msg) {
    e.preventDefault();
    if (confirm(msg)) {
      window.location.href = e.target.href;
    }
    return false;
  }
</script>

</html>