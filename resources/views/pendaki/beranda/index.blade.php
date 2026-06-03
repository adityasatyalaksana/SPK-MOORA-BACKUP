@extends('layouts.admin')

@section('content')
<style>
    /* Premium Theme Variables & Core Styles */
    :root {
        --forest-dark: #0f2d1e;
        --forest-primary: #198754;
        --forest-gradient-1: #115e59;
        --forest-gradient-2: #0f766e;
        --forest-accent: #0d9488;
        --soft-bg: #f4f7f6;
        --emerald-shadow: rgba(13, 148, 136, 0.12);
        --transition-premium: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    /* Page-wide settings */
    .beranda-container {
        background-color: var(--soft-bg);
        min-height: 100vh;
    }

    /* Hero Banner with Sleek Forest Gradient & Glassmorphism */
    .hero-banner {
        position: relative;
        border-radius: 24px;
        overflow: hidden;
        background: linear-gradient(135deg, var(--forest-gradient-1) 0%, var(--forest-gradient-2) 100%);
        box-shadow: 0 10px 30px var(--emerald-shadow);
        z-index: 1;
    }

    .hero-banner::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('https://images.unsplash.com/photo-1501555088652-021faa106b9b?auto=format&fit=crop&w=1500&q=80') no-repeat center center;
        background-size: cover;
        opacity: 0.15;
        z-index: -1;
    }

    .hero-glass-card {
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
    }

    /* Stats Card Hover Effect */
    .premium-stat-card {
        border-radius: 20px !important;
        border: 1px solid rgba(226, 232, 240, 0.8) !important;
        background: #ffffff;
        transition: var(--transition-premium);
    }
    
    .premium-stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px var(--emerald-shadow) !important;
        border-color: var(--forest-accent) !important;
    }

    /* Mountain Showcase Cards */
    .popular-mountain-card {
        border-radius: 20px !important;
        overflow: hidden;
        transition: var(--transition-premium);
        border: 1px solid rgba(226, 232, 240, 0.8) !important;
    }

    .popular-mountain-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(25, 135, 84, 0.12) !important;
    }

    .m-img-container {
        position: relative;
        height: 200px;
        overflow: hidden;
        background-color: #e2e8f0;
    }

    .m-img-container img {
        transition: transform 0.6s ease;
    }

    .popular-mountain-card:hover .m-img-container img {
        transform: scale(1.08);
    }

    .m-elevation-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(15, 23, 42, 0.8);
        backdrop-filter: blur(6px);
        color: #fff;
        padding: 5px 12px;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 700;
        z-index: 10;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    /* Workflow Cards */
    .workflow-card {
        border-radius: 16px !important;
        transition: var(--transition-premium);
        border: 1px solid #e2e8f0 !important;
    }

    .workflow-card:hover {
        transform: translateY(-4px);
        border-color: var(--forest-accent) !important;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.04) !important;
    }

    .step-number {
        font-size: 2.2rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--forest-gradient-1), var(--forest-accent));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        line-height: 1;
    }
</style>

<div class="container-fluid beranda-container p-4">
    
    {{-- Hero Banner --}}
    <div class="card border-0 hero-banner mb-4 p-4 p-md-5">
        <div class="row align-items-center">
            <div class="col-lg-7 text-white">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2" style="border-radius: 10px; font-weight: 700;">
                        🏔️ SPK MOORA Edisi Jawa Barat
                    </span>
                </div>
                <h1 class="display-4 fw-bold mb-3" style="letter-spacing: -1px;">Halo, Pendaki Tangguh! 👋</h1>
                <p class="lead text-white-50 mb-4" style="font-size: 1.1rem; line-height: 1.6;">
                    Selamat datang di platform **Sistem Pendukung Keputusan Pemilihan Jalur Pendakian**. Siap menjelajahi keindahan dan menaklukkan gunung impian Anda dengan budget paling optimal hari ini?
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('rekomendasi.index') }}" class="btn btn-light btn-lg px-4 py-2½ shadow-sm d-flex align-items-center gap-2" style="border-radius: 12px; font-size: 0.95rem; font-weight: 700; color: var(--forest-gradient-1);">
                        <i class="bi bi-compass-fill"></i> Cari Rekomendasi Jalur
                    </a>
                    <a href="{{ route('pendaki.profile.index') }}" class="btn btn-outline-light btn-lg px-4 py-2½ d-flex align-items-center gap-2" style="border-radius: 12px; font-size: 0.95rem; font-weight: 600;">
                        <i class="bi bi-grid-fill"></i> Jelajahi Profil Gunung
                    </a>
                </div>
            </div>
            <div class="col-lg-5 mt-4 mt-lg-0 text-center text-lg-end">
                <div class="hero-glass-card p-4 text-white text-start">
                    <h5 class="fw-bold mb-2"><i class="bi bi-info-circle-fill text-warning me-2"></i>Tentang Platform</h5>
                    <p class="small text-white-50 mb-0" style="line-height: 1.6;">
                        Aplikasi ini menggunakan algoritma **MOORA (Multi-Objective Optimization on the basis of Ratio Analysis)** untuk memperhitungkan berbagai variabel kriteria (Biaya, Ketinggian, Waktu, Kesulitan) sehingga menghasilkan saran pendakian terbaik yang disesuaikan khusus untuk Anda.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="row g-3 mb-5">
        <div class="col-md-4">
            <div class="card premium-stat-card shadow-sm h-100">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-secondary small mb-1 text-uppercase tracking-wider fw-bold">Pangkalan Data Gunung</h6>
                        <h2 class="fw-bold text-dark mb-0">{{ $totalGunung ?? 0 }} <span class="fs-5 text-muted fw-normal">Gunung</span></h2>
                    </div>
                    <div class="p-3 bg-success-subtle text-success rounded-4">
                        <i class="bi bi-tree-fill fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card premium-stat-card shadow-sm h-100">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-secondary small mb-1 text-uppercase tracking-wider fw-bold">Jalur Resmi Terdaftar</h6>
                        <h2 class="fw-bold text-dark mb-0">{{ $totalJalur ?? 0 }} <span class="fs-5 text-muted fw-normal">Opsi Jalur</span></h2>
                    </div>
                    <div class="p-3 bg-info-subtle text-info rounded-4">
                        <i class="bi bi-signpost-split-fill fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card premium-stat-card shadow-sm h-100">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-secondary small mb-1 text-uppercase tracking-wider fw-bold">Kriteria Optimasi MOORA</h6>
                        <h2 class="fw-bold text-dark mb-0">{{ $totalKriteria ?? 0 }} <span class="fs-5 text-muted fw-normal">Kriteria</span></h2>
                    </div>
                    <div class="p-3 bg-primary-subtle text-primary rounded-4">
                        <i class="bi bi-sliders fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- Workflow Steps --}}
    <h4 class="fw-bold text-dark mb-4 text-center text-md-start">Cara Mendapatkan Rekomendasi Jalur</h4>
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card workflow-card shadow-sm h-100 bg-white">
                <div class="card-body p-4 text-center text-md-start">
                    <div class="d-flex align-items-center justify-content-center justify-content-md-between mb-3">
                        <span class="step-number">01</span>
                        <div class="p-2 bg-success-subtle text-success rounded-3">
                            <i class="bi bi-binoculars fs-4"></i>
                        </div>
                    </div>
                    <h6 class="fw-bold text-dark mb-2">Pelajari Profil Gunung</h6>
                    <p class="small text-muted mb-0" style="line-height: 1.6;">
                        Buka menu <a href="{{ route('pendaki.profile.index') }}" class="text-success fw-bold text-decoration-none">Profil Gunung</a> untuk melihat informasi tinggi gunung, peta lokasi, deskripsi, serta opsi jalur resmi yang tersedia di Jawa Barat.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card workflow-card shadow-sm h-100 bg-white">
                <div class="card-body p-4 text-center text-md-start">
                    <div class="d-flex align-items-center justify-content-center justify-content-md-between mb-3">
                        <span class="step-number">02</span>
                        <div class="p-2 bg-info-subtle text-info rounded-3">
                            <i class="bi bi-sliders fs-4"></i>
                        </div>
                    </div>
                    <h6 class="fw-bold text-dark mb-2">Atur Kriteria Preferensi Anda</h6>
                    <p class="small text-muted mb-0" style="line-height: 1.6;">
                        Masuk ke menu <a href="{{ route('rekomendasi.index') }}" class="text-info fw-bold text-decoration-none">Cari Rekomendasi</a>, lalu tentukan seberapa penting kriteria biaya, tingkat kesulitan jalur, dan durasi pendakian sesuai kondisi fisik dan budget Anda.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card workflow-card shadow-sm h-100 bg-white">
                <div class="card-body p-4 text-center text-md-start">
                    <div class="d-flex align-items-center justify-content-center justify-content-md-between mb-3">
                        <span class="step-number">03</span>
                        <div class="p-2 bg-primary-subtle text-primary rounded-3">
                            <i class="bi bi-signpost-fill fs-4"></i>
                        </div>
                    </div>
                    <h6 class="fw-bold text-dark mb-2">Dapatkan Rekomendasi Optimal</h6>
                    <p class="small text-muted mb-0" style="line-height: 1.6;">
                        Tekan tombol proses untuk melihat peringkat jalur pendakian terbaik yang diurutkan secara matematis oleh sistem berdasarkan kesesuaian tertinggi dari budget dan preferensi Anda.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Utility colors for sub-gradients */
    .bg-success-subtle { background-color: #e8f5e9 !important; border-color: #c8e6c9 !important; }
    .bg-info-subtle { background-color: #e3f2fd !important; border-color: #bbdefb !important; }
    .bg-primary-subtle { background-color: #eff6ff !important; border-color: #dbeafe !important; }
    .text-success { color: #157347 !important; }
    .text-info { color: #0284c7 !important; }
    .text-primary { color: #2563eb !important; }
    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection