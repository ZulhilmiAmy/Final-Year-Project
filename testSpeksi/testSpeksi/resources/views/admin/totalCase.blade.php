{{-- resources/views/admin/totalCase.blade.php --}}
<!DOCTYPE html>
<html lang="ms">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPeKSi - Jumlah Kes {{ now()->year }}</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" href="{{ asset('css/totalCase.css') }}?v={{ time() }}">
</head>

<body>

    <!-- 🔹 Header Banner -->
    <div class="header">
        <img src="https://raw.githubusercontent.com/ZulhilmiAmy/Final-Year-Project/main/Image%20banner/Banner-SPeKSi.png"
            alt="Banner SPeKSi">
    </div>

    <!-- 🔹 Top Navigation -->
    <div class="top-bar">
        <div class="breadcrumbs">
            <a>Log Masuk</a> &gt;
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.home') }}">Halaman Utama</a>
            @else
                <a href="{{ route('home') }}">Halaman Utama</a>
            @endif &gt;
            <a>Jumlah Kes</a>
        </div>

    </div>

    <!-- 🔹 Content -->
    <div class="container">
        <h1>📊 Jumlah Kes {{ now()->year }}</h1>

        <div class="summary-card">
            <div>Jumlah Kes Tahun {{ now()->year }}</div>
            <div class="highlight">{{ $totalCasesYear }}</div>
        </div>

        <!-- Table -->
        <div class="data-card">
            <div class="card-header">Jumlah Kes Mengikut Bulan</div>
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th>Jumlah Kes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($casesByMonth as $case)
                        <tr>
                            <td>{{ \Carbon\Carbon::create()->month($case->month)->translatedFormat('F') }}</td>
                            <td>{{ $case->total }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Chart -->
        <div class="data-card">
            <div class="card-header">Graf Kes Mengikut Bulan ({{ now()->year }})</div>
            <canvas id="casesChart"></canvas>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('casesChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($casesByMonth->pluck('month')->map(fn($m) => \Carbon\Carbon::create()->month($m)->translatedFormat('F'))) !!},
                datasets: [{
                    label: 'Jumlah Kes',
                    data: {!! json_encode($casesByMonth->pluck('total')) !!},
                    backgroundColor: '#204d84'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    </script>
</body>

</html>