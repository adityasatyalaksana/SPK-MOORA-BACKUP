@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4" style="background: #f8f9fc;">
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="fw-bold text-dark">Ringkasan Sistem Perhitungan MOORA</h4>
            <p class="text-muted small">Selamat datang kembali, berikut adalah ringkasan data skripsi Anda hari ini.</p>
        </div>
    </div>

    <div class="row mb-4">
        @php
            $stats = [
                ['label' => 'Total Gunung', 'val' => $data['total_gunung'], 'icon' => 'fa-mountain', 'color' => 'primary'],
                ['label' => 'Jalur Pendakian', 'val' => $data['total_jalur'], 'icon' => 'fa-route', 'color' => 'success'],
                ['label' => 'Kriteria MOORA', 'val' => $data['total_kriteria'], 'icon' => 'fa-list-check', 'color' => 'info'],
                ['label' => 'Total Pengguna', 'val' => $data['total_user'], 'icon' => 'fa-users', 'color' => 'dark'],
            ];
        @endphp

        @foreach($stats as $s)
        <div class="col-md-3 col-6 mb-3">
            <div class="card border-0 shadow-sm h-100 py-2" style="border-left: 4px solid var(--bs-{{ $s['color'] }}) !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-{{ $s['color'] }} text-uppercase mb-1" style="font-size: 0.7rem;">{{ $s['label'] }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $s['val'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas {{ $s['icon'] }} fa-2x text-gray-300" style="color: #dddfeb;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Proporsi Bobot Kriteria</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4">
                        <canvas id="kriteriaPieChart"></canvas>
                    </div>
                    <hr>
                    <div class="text-center small">
                        <span class="mr-2"><i class="fas fa-circle text-primary"></i> Data terintegrasi dengan metode MOORA</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7">
            <div class="card shadow-sm border-0 mb-4 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tentang Implementasi Sistem</h6>
                </div>
                <div class="card-body">
                    <div class="p-4 bg-light rounded border-start border-4 border-primary">
                        <h5>Sistem Pendukung Keputusan (SPK)</h5>
                        <p class="mb-2">Metode <strong>MOORA</strong> pada sistem ini dioptimalkan menggunakan pendekatan <strong>Sinkronisasi Nilai Preferensi Positif (Full Benefit Criteria)</strong>.</p>
                        <p class="mb-0 text-muted small">Seluruh parameter penilaian ditransformasikan menjadi tipe kriteria keuntungan, di mana kondisi paling ideal bagi pendaki dikonversi menjadi bobot preferensi tertinggi:</p>
                        <ul class="mt-2 mb-0 fw-bold text-secondary" style="font-size: 0.9rem;">
                            <li><i class="fas fa-check text-success me-1"></i> Nilai Maksimum (Skor 5): Merepresentasikan biaya paling murah, waktu paling efisien, dan rute ideal.</li>
                            <li><i class="fas fa-check text-success me-1"></i> Nilai Minimum (Skor 1): Merepresentasikan biaya tinggi, waktu perjalanan lama, atau medan berat.</li>
                        </ul>
                    </div>
                    <div class="mt-4 text-center">
                        <a href="{{ route('hasil.perhitungan') }}" class="btn btn-primary px-4 shadow-sm">
                            <i class="fas fa-calculator me-2"></i> Jalankan Perhitungan MOORA Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('kriteriaPieChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
        labels: @json($chartLabels),
        datasets: [{
            data: @json($chartWeights),
            // ... sisa kode lainnya
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'],
                hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#dda20a', '#be2617', '#60616f'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: { display: true, position: 'bottom' },
                // FIX: Gunakan format callback yang lebih aman ini
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            // Ambil nama label kriteria (misal: Biaya Transportasi)
                            let label = context.label || '';
                            // Ambil nilai angka mentahnya
                            let value = context.parsed !== undefined ? context.parsed : context.raw;
                            
                            // Gabungkan label dengan angka yang dipaksa 2 desimal (0.30)
                            return ' ' + label + ': ' + Number(value).toFixed(2);
                        }
                    }
                }
            },
            cutout: '70%',
        },
    });
</script>
@endsection