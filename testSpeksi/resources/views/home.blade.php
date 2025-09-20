<!DOCTYPE html>
<html lang="ms">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>SPeKSi - DAFTAR PERMOHONAN BARU</title>

  <!-- FullCalendar (CDN) -->
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales/ms.global.min.js"></script>

  <!-- ✅ Inject URLs supaya boleh guna dalam home.js -->
  <script>
    window.patientsSearchUrl = "{{ route('patients.search') }}";
    window.patientsHistoryUrl = "{{ route('patients.history') }}";
    window.patientsCreateUrl = "{{ route('patients.create') }}";
    window.appointmentsUpcomingUrl = "{{ route('appointments.upcoming') }}";
  </script>

  <link rel="stylesheet" href="{{ asset('css/home.css') }}?v={{ time() }}">
</head>


<body>

  @if(!Auth::check())
    <script>
      window.location.href = "{{ route('login.custom') }}";
    </script>
  @endif
  <!-- header -->
  <div class="header">
    <img src="https://raw.githubusercontent.com/ZulhilmiAmy/Final-Year-Project/main/Image%20banner/Banner-SPeKSi.png"
      alt="Banner" onerror="this.style.display='none'">
  </div>

  <div class="top-bar">
    <div class="breadcrumbs">
      <a href="#" onclick="confirmGoLogin('{{ route('login.custom') }}')">Log Masuk</a> &gt;
      <a>Halaman Utama</a>
    </div>

    <div class="greeting">
      👋 Hi User, {{ Auth::user()->name }}
    </div>

    <!-- Laravel logout form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
      @csrf
    </form>
    <button class="logout-btn" onclick="confirmLogout()">
      <i class="fas fa-sign-out-alt"></i> Log Keluar
    </button>
  </div>

  @if(session('success'))
    <div id="successPopup" style="
                  position: fixed;
                  top: 20px;
                  right: 20px;
                  background: #28a745;
                  color: white;
                  padding: 15px 20px;
                  border-radius: 8px;
                  box-shadow: 0 4px 6px rgba(0,0,0,0.2);
                  z-index: 9999;
                  font-size: 15px;
                  opacity: 1;
                  transition: opacity 0.5s ease-out;
              ">
      ✅ {{ session('success') }}
    </div>

    <script>
      // Hilang automatik selepas 5 saat
      setTimeout(() => {
        const popup = document.getElementById('successPopup');
        if (popup) {
          popup.style.opacity = 0;
          setTimeout(() => popup.remove(), 500); // remove selepas fade
        }
      }, 5000);
    </script>
  @endif

  @if(session('pdf_url'))
    <a id="autoPdfLink" href="{{ session('pdf_url') }}" target="_blank" style="display:none;"></a>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("autoPdfLink").click();
      });
    </script>
  @endif


  <!-- kontena utama -->
  <div class="container">
    <div class="title">DAFTAR PERMOHONAN BARU</div>

    <label for="noKP"><b>Carian No.KP Pemohon</b></label>
    <div class="input-container">
      <input type="text" id="noKP" name="noKP" placeholder="Masukkan No.KP Pemohon" maxlength="12" />
      <button class="clear-btn" id="clearBtn">x</button>
    </div>

    <div id="recordDisplay" class="record-display">
      <h2>PAPARAN REKOD PEMOHON</h2>
      <div id="recordDetails"></div>
      <div id="recordButtons" class="options"></div>
    </div>
  </div>

  <!-- Kalendar + Peringatan -->
  <div class="calendar-reminder-container">
    <!-- Calendar -->
    <div id="calendar"></div>

    <!-- Reminder box -->
    <div class="reminder-box">
      <h2>Pegawai Kes</h2>
      <div id="pegawai-buttons"></div>
      <hr>
      <h3>Temujanji Akan Datang</h3>
      <div id="reminder-list"></div>
    </div>
  </div>

  <!-- Statistik -->
  <div class="stats-container">
    <div class="stats-title">Statistik Permohonan 2025</div>
    <div class="stats-grid">
      <div class="stat-box green">
        JUMLAH KES
        <div style="font-size:20px; margin-top:8px;">
          Tahun {{ now()->year }}: <strong>{{ $totalCasesYear }}</strong>
        </div>
        <div style="font-size:20px; margin-top:4px;">
          Bulan {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}: <strong>{{ $totalCasesMonth }}</strong>
        </div>
        <a href="{{ route('admin.totalCase') }}" class="desc-btn">Keterangan</a>
      </div>
      <div class="stat-box blue">
        KPI 1
        <div style="font-size:28px;">1</div>
        <div>(8%)</div>
        <a href="#" class="desc-btn">Keterangan</a>
      </div>
      <div class="stat-box orange">
        KPI 2
        <div style="font-size:28px;">0</div>
        <div>(0%)</div>
        <a href="#" class="desc-btn">Keterangan</a>
      </div>
    </div>
  </div>

  <!-- Modal (tambah/padam) -->
  <div id="modalBackdrop" class="modal-backdrop" aria-hidden="true">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
      <h3 id="modalTitle">Urus Temujanji</h3>
      <p id="modalDateLabel">Tarikh: </p>

      <div>
        <label>Temujanji sedia ada pada tarikh ini:</label>
        <ul class="events-list" id="modalEventsList"></ul>
      </div>

      <div style="margin-top:8px;">
        <label for="newEventTitle">Tambah Temujanji Baru</label>
        <input type="text" id="newEventTitle" placeholder="Nama pesakit..." />
      </div>

      <div class="modal-actions">
        <button class="btn btn-muted" id="modalCloseBtn">Tutup</button>
        <button class="btn btn-primary" id="modalAddBtn">Tambah</button>
      </div>
    </div>
  </div>
  <script src="{{ asset('js/home.js') }}?v={{ time() }}"></script>
</body>

<script>
  function newApplication() {
    let kp = document.getElementById('noKP').value;
    window.location.href = "{{ route('patients.create') }}?kp=" + encodeURIComponent(kp);
  }
</script>


</html>