@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">
    
    <div class="card border-0 shadow-sm mb-4 overflow-hidden" style="border-radius: 15px;">
        <div class="card-body p-0">
            <div class="p-5 text-white" style="background: linear-gradient(rgba(25, 135, 84, 0.8), rgba(25, 135, 84, 0.8)), url('https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=1000&q=80'); background-size: cover; background-position: center;">
                <h1 class="display-5 fw-bold">Halo, Pendaki!</h1>
                <p class="lead text-white-50">Siap menjelajahi keindahan gunung di Jawa Barat hari ini?</p>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-3 text-dark">Sistem Pendukung Keputusan MOORA</h4>
                    <p class="text-muted" style="line-height: 1.8;">
                        Selamat datang di aplikasi **Estimasi Budget Pendakian**. Aplikasi ini dirancang untuk membantu Anda menemukan jalur pendakian yang paling optimal. Dengan memasukkan kriteria yang Anda inginkan, algoritma kami akan menghitung secara otomatis jalur mana yang paling sesuai dengan anggaran dan kemampuan fisik Anda.
                    </p>
                    <hr class="my-4 opacity-25">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-success-subtle p-2 rounded-3 me-3">
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                </div>
                                <span class="small fw-bold">Data Jalur Terverifikasi</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-success-subtle p-2 rounded-3 me-3">
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                </div>
                                <span class="small fw-bold">Estimasi Biaya Realistis</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 bg-success text-white shadow-sm h-100" style="border-radius: 15px;">
                <div class="card-body p-4 d-flex flex-column justify-content-center text-center">
                    <i class="bi bi-lightning-charge-fill mb-3" style="font-size: 3rem;"></i>
                    <h5 class="fw-bold">Mulai Sekarang</h5>
                    <p class="small text-white-50 mb-4">Dapatkan rekomendasi gunung terbaik hanya dalam hitungan detik.</p>
                    <a href="{{ route('rekomendasi.index') }}" class="btn btn-light fw-bold py-2 shadow-sm">
                        Cari Rekomendasi
                    </a>
                </div>
            </div>
        </div>
    </div>

    <h5 class="fw-bold mb-3 text-gray-800">Bagaimana Cara Kerjanya?</h5>
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-4">
                    <div class="h3 fw-bold text-success-emphasis mb-2">01</div>
                    <h6 class="fw-bold">Tentukan Kriteria</h6>
                    <p class="small text-muted mb-0">Pilih bobot kriteria yang Anda inginkan seperti budget, kesulitan, dan fasilitas.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-4">
                    <div class="h3 fw-bold text-success-emphasis mb-2">02</div>
                    <h6 class="fw-bold">Proses MOORA</h6>
                    <p class="small text-muted mb-0">Sistem akan melakukan kalkulasi normalisasi dan optimasi secara matematis.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-4">
                    <div class="h3 fw-bold text-success-emphasis mb-2">03</div>
                    <h6 class="fw-bold">Hasil Rekomendasi</h6>
                    <p class="small text-muted mb-0">Lihat daftar gunung terbaik yang diurutkan berdasarkan nilai skor tertinggi.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-success-subtle {
        background-color: #d1e7dd;
    }
    .text-success-emphasis {
        color: #055160;
        opacity: 0.3;
    }
    .card {
        transition: transform 0.2s;
    }
    .card:hover {
        transform: translateY(-3px);
    }
</style>
@endsection