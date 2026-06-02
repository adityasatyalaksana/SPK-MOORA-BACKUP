@extends('layouts.admin')

@section('content')
<!-- Google Fonts: Outfit -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    /* Scope dashboard styles to avoid breaking other pages */
    .dashboard-body {
        font-family: 'Outfit', sans-serif;
        background-color: #f1f5f9;
        min-height: 100vh;
        color: #1e293b;
    }

    .welcome-banner {
        background: linear-gradient(135deg, #0f172a, #064e3b);
        border-radius: 16px;
        padding: 35px 30px;
        position: relative;
        overflow: hidden;
        color: #ffffff;
        box-shadow: 0 10px 25px rgba(6, 78, 59, 0.15);
    }

    .welcome-banner::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 300px;
        height: 100%;
        background-image: url('https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=600&q=80');
        background-size: cover;
        background-position: center;
        opacity: 0.1;
        mask-image: linear-gradient(to left, rgba(0,0,0,1), rgba(0,0,0,0));
        -webkit-mask-image: linear-gradient(to left, rgba(0,0,0,1), rgba(0,0,0,0));
        pointer-events: none;
    }

    .stat-widget {
        background: #ffffff;
        border-radius: 16px;
        border: none;
        padding: 24px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-widget:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08);
    }

    .stat-widget::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
    }

    .widget-gunung::before { background-color: #10b981; }
    .widget-jalur::before { background-color: #06b6d4; }
    .widget-kriteria::before { background-color: #6366f1; }
    .widget-user::before { background-color: #f59e0b; }

    .icon-badge {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .badge-gunung { background-color: #ecfdf5; color: #10b981; }
    .badge-jalur { background-color: #ecfeff; color: #06b6d4; }
    .badge-kriteria { background-color: #eef2ff; color: #6366f1; }
    .badge-user { background-color: #fffbeb; color: #f59e0b; }

    .card-premium {
        background: #ffffff;
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }

    .card-premium-header {
        background: #ffffff;
        border-bottom: 1px solid #f1f5f9;
        padding: 20px 24px;
    }

    .info-alert {
        background-color: #f8fafc;
        border-left: 4px solid #10b981;
        border-radius: 8px;
        padding: 20px;
    }

    .action-btn-custom {
        background: linear-gradient(135deg, #10b981, #047857);
        border: none;
        border-radius: 12px;
        color: #ffffff;
        font-weight: 600;
        padding: 14px 28px;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
        transition: all 0.3s ease;
    }

    .action-btn-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.35);
        color: #ffffff;
        filter: brightness(1.05);
    }

    .action-btn-custom:hover, .action-btn-custom:focus {
        color: #ffffff;
    }

    .quick-action-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        text-decoration: none;
        color: #1e293b;
    }

    .quick-action-card:hover {
        background: #ffffff;
        border-color: #10b981;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transform: translateY(-2px);
        color: #10b981;
    }
</style>

<div class="dashboard-body container-fluid p-4">
    @if(session('welcome'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4 d-flex align-items-center" role="alert" style="background-color: #ecfdf5; border-left: 4px solid #10b981 !important; border-radius: 12px; color: #065f46;">
            <i class="bi bi-check-circle-fill me-3 fs-5" style="color: #10b981;"></i>
            <div class="fw-semibold">{{ session('welcome') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Welcome Banner Section -->
    <div class="welcome-banner mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge bg-success bg-opacity-25 px-3 py-2 mb-2" style="font-size: 0.75rem; letter-spacing: 0.5px; border: 1px solid rgba(16, 185, 129, 0.3); color: #4ade80 !important;">SPK METODE MOORA</span>
                <h2 class="fw-bold mb-2">Selamat Datang Kembali, {{ auth()->user()->name }}! 👋</h2>
                <p class="mb-0 opacity-75">Sistem Pendukung Keputusan Pemilihan Jalur Pendakian Gunung. Kelola data master, tentukan kriteria, dan dapatkan perhitungan rekomendasi jalur terbaik secara real-time.</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <!-- Card Gunung -->
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-widget widget-gunung h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small text-uppercase fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Total Gunung</div>
                        <h3 class="fw-bold mb-0 text-dark">{{ $data['total_gunung'] }}</h3>
                    </div>
                    <div class="icon-badge badge-gunung">
                        <i class="bi bi-layers"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Jalur -->
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-widget widget-jalur h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small text-uppercase fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Jalur Pendakian</div>
                        <h3 class="fw-bold mb-0 text-dark">{{ $data['total_jalur'] }}</h3>
                    </div>
                    <div class="icon-badge badge-jalur">
                        <i class="bi bi-signpost-2"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Kriteria -->
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-widget widget-kriteria h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small text-uppercase fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Kriteria MOORA</div>
                        <h3 class="fw-bold mb-0 text-dark">{{ $data['total_kriteria'] }}</h3>
                    </div>
                    <div class="icon-badge badge-kriteria">
                        <i class="bi bi-list-check"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Users -->
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-widget widget-user h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small text-uppercase fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Total Pengguna</div>
                        <h3 class="fw-bold mb-0 text-dark">{{ $data['total_user'] }}</h3>
                    </div>
                    <div class="icon-badge badge-user">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Section -->
    <div class="row">
        <!-- Left Column: Pie Chart -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card-premium h-100">
                <div class="card-premium-header">
                    <h6 class="m-0 fw-bold text-dark"><i class="bi bi-pie-chart me-2 text-success"></i>Proporsi Bobot Kriteria</h6>
                </div>
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div class="chart-container" style="position: relative; height:240px;">
                        <canvas id="kriteriaPieChart"></canvas>
                    </div>
                    <div class="text-center small mt-4 p-2 bg-light rounded border">
                        <span class="text-muted"><i class="bi bi-circle-fill text-success me-1" style="font-size: 0.5rem; vertical-align: middle;"></i> Data terintegrasi dengan perhitungan MOORA</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Info & Quick Actions -->
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card-premium h-100 p-4">
                <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-info-circle me-2 text-success"></i>Tentang Implementasi Sistem</h5>
                
                <div class="info-alert mb-4">
                    <h6 class="fw-bold text-success mb-2">Metode MOORA Teroptimasi</h6>
                    <p class="mb-2 text-secondary" style="font-size: 0.95rem;">Sistem pendukung keputusan ini menggunakan metode **MOORA (Multi-Objective Optimization on the basis of Ratio Analysis)** dengan pendekatan **Sinkronisasi Nilai Preferensi Positif (Full Benefit Criteria)**.</p>
                    <p class="mb-0 text-muted small">Seluruh parameter penilaian ditransformasikan menjadi tipe kriteria keuntungan, di mana kondisi paling ideal bagi pendaki dikonversi menjadi bobot preferensi tertinggi:</p>
                    <ul class="mt-2 mb-0 fw-bold text-secondary" style="font-size: 0.85rem; list-style: none; padding-left: 0;">
                        <li class="mb-1"><i class="bi bi-check-circle-fill text-success me-2"></i> Nilai Maksimum (Skor 5): Biaya paling murah, waktu paling efisien, dan rute ideal.</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i> Nilai Minimum (Skor 1): Biaya tinggi, waktu perjalanan lama, atau medan pendakian berat.</li>
                    </ul>
                </div>



                <div class="text-center pt-2">
                    <a href="{{ route('hasil.perhitungan') }}" class="action-btn-custom btn w-100 py-3">
                        <i class="bi bi-calculator me-2"></i> Jalankan Perhitungan MOORA Sekarang
                    </a>
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
                backgroundColor: ['#10b981', '#06b6d4', '#6366f1', '#f59e0b', '#ef4444', '#64748b'],
                hoverBackgroundColor: ['#059669', '#0891b2', '#4f46e5', '#d97706', '#dc2626', '#475569'],
                hoverBorderColor: "rgba(255, 255, 255, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: { display: true, position: 'bottom', labels: { boxWidth: 12, padding: 15, font: { family: 'Outfit', size: 11 } } },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            let value = context.parsed !== undefined ? context.parsed : context.raw;
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