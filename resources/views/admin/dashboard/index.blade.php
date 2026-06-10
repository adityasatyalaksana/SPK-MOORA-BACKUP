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
    .widget-terminal::before { background-color: #0284c7; }
    .widget-biaya::before { background-color: #a855f7; }
    .widget-penilaian::before { background-color: #ec4899; }
    .widget-log::before { background-color: #64748b; }

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
    .badge-terminal { background-color: #f0f9ff; color: #0284c7; }
    .badge-biaya { background-color: #faf5ff; color: #a855f7; }
    .badge-penilaian { background-color: #fdf2f8; color: #ec4899; }
    .badge-log { background-color: #f1f5f9; color: #64748b; }

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

    .bg-purple-subtle {
        background-color: #faf5ff !important;
        color: #a855f7 !important;
        border: 1px solid rgba(168, 85, 247, 0.2) !important;
    }

    /* custom grid for 5 columns on medium and large screens */
    @media (min-width: 768px) {
        .col-md-2-5 {
            flex: 0 0 20%;
            max-width: 20%;
        }
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

    <!-- 3 Besar Alternatif Rute Terbaik (MOORA) -->
    @if(!empty($topAlternatives))
    <div class="card card-premium border-0 shadow-sm mb-4">
        <div class="card-premium-header bg-dark text-white p-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 fw-bold d-flex align-items-center text-white">
                <i class="bi bi-trophy-fill text-warning me-2 fs-5"></i>
                3 Besar Rute Terbaik (Rekomendasi Utama Hasil MOORA)
            </h6>
            <a href="{{ route('hasil.perhitungan') }}" class="btn btn-sm btn-outline-light" style="border-radius: 8px;">Detail Perhitungan</a>
        </div>
        <div class="card-body p-4">
            <div class="row">
                @foreach($topAlternatives as $index => $alt)
                    @php
                        $medals = [
                            0 => ['icon' => 'bi-award-fill', 'color' => '#eab308', 'bg' => '#fef9c3', 'label' => 'Rekomendasi Utama'],
                            1 => ['icon' => 'bi-award-fill', 'color' => '#64748b', 'bg' => '#f1f5f9', 'label' => 'Peringkat 2'],
                            2 => ['icon' => 'bi-award-fill', 'color' => '#b45309', 'bg' => '#ffedd5', 'label' => 'Peringkat 3']
                        ];
                        $medal = $medals[$index];
                    @endphp
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="p-3 rounded-4 border h-100 d-flex flex-column justify-content-between" style="background-color: {{ $medal['bg'] }}; border-color: rgba(0,0,0,0.05) !important;">
                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <span class="badge px-3 py-2 rounded-pill d-inline-flex align-items-center gap-1 small fw-bold" style="background-color: #ffffff; color: {{ $medal['color'] }}; border: 1px solid rgba(0,0,0,0.05); font-size: 0.72rem;">
                                        <i class="bi {{ $medal['icon'] }} fs-6"></i> {{ $medal['label'] }}
                                    </span>
                                    <span class="fw-bold fs-5" style="color: {{ $medal['color'] }};">#{{ $index + 1 }}</span>
                                </div>
                                <h5 class="fw-bold text-dark mb-1">Gn. {{ $alt['nama_gunung'] }}</h5>
                                <p class="text-secondary small mb-2">Via {{ $alt['nama_jalur'] }}</p>
                                <div class="text-muted small mb-3">
                                    <i class="bi bi-bus-front me-1 text-primary"></i> <strong>{{ $alt['nama_armada'] }}</strong> <br>
                                    <span class="text-secondary d-inline-block mt-1" style="font-size: 0.75rem;">
                                        ({{ $alt['start_terminal'] }} &rarr; {{ $alt['end_terminal'] }})
                                    </span>
                                </div>
                            </div>
                            <div class="pt-2 border-top border-secondary border-opacity-10 d-flex justify-content-between align-items-center">
                                <span class="text-muted small">Skor Optimasi:</span>
                                <span class="fw-bold text-dark font-monospace">{{ number_format($alt['skor'], 4) }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <!-- Card Gunung -->
        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
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
        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
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

        <!-- Card Terminal -->
        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
            <div class="stat-widget widget-terminal h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small text-uppercase fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Terminal Transit</div>
                        <h3 class="fw-bold mb-0 text-dark">{{ $data['total_terminal'] }}</h3>
                    </div>
                    <div class="icon-badge badge-terminal">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Armada Bus -->
        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
            <div class="stat-widget widget-biaya h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small text-uppercase fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Armada &amp; Tarif</div>
                        <h3 class="fw-bold mb-0 text-dark">{{ $data['total_biaya'] }}</h3>
                    </div>
                    <div class="icon-badge badge-biaya">
                        <i class="bi bi-bus-front"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Penilaian Terisi -->
        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
            <div class="stat-widget widget-penilaian h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small text-uppercase fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Penilaian Terisi</div>
                        <h3 class="fw-bold mb-0 text-dark">{{ $data['total_penilaian'] }}</h3>
                    </div>
                    <div class="icon-badge badge-penilaian">
                        <i class="bi bi-check2-square"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Kriteria -->
        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
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
        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
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

        <!-- Card Log Aktivitas -->
        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
            <div class="stat-widget widget-log h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small text-uppercase fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Log Aktivitas</div>
                        <h3 class="fw-bold mb-0 text-dark">{{ $data['total_logs'] }}</h3>
                    </div>
                    <div class="icon-badge badge-log">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Section -->
    <div class="row">
        <!-- Wide Column: Horizontal Bar Chart -->
        <div class="col-12 mb-4">
            <div class="card-premium h-100">
                <div class="card-premium-header">
                    <h6 class="m-0 fw-bold text-dark"><i class="bi bi-bar-chart-line-fill me-2 text-success"></i>Perbandingan Bobot Kriteria (Horizontal Bar Chart)</h6>
                </div>
                <div class="card-body p-4">
                    <div class="chart-container" style="position: relative; height:320px;">
                        <canvas id="kriteriaBarChart"></canvas>
                    </div>
                    <div class="text-center small mt-4 p-2 bg-light rounded border">
                        <span class="text-muted"><i class="bi bi-circle-fill text-success me-1" style="font-size: 0.5rem; vertical-align: middle;"></i> Data bobot kriteria terintegrasi secara dinamis dengan metode MOORA</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('kriteriaBarChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Bobot Kriteria',
                data: @json($chartWeights),
                backgroundColor: ['#10b981', '#06b6d4', '#6366f1', '#f59e0b', '#ef4444', '#64748b'],
                hoverBackgroundColor: ['#059669', '#0891b2', '#4f46e5', '#d97706', '#dc2626', '#475569'],
                borderRadius: 8,
                borderSkipped: false,
                barThickness: 24
            }],
        },
        options: {
            indexAxis: 'y',
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            let value = context.parsed.x !== undefined ? context.parsed.x : context.raw;
                            return ' ' + label + ': ' + Number(value).toFixed(2);
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: '#f1f5f9' },
                    ticks: { font: { family: 'Outfit', size: 12 }, color: '#64748b' }
                },
                y: {
                    grid: { display: false },
                    ticks: { font: { family: 'Outfit', size: 12, weight: '500' }, color: '#1e293b' }
                }
            }
        },
    });
</script>
@endsection