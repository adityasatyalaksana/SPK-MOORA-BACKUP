@extends('layouts.admin')

@section('content')
<style>
    /* Custom Styling untuk Eksplorasi Premium */
    .page-title-gradient {
        background: linear-gradient(90deg, #198754 0%, #146c43 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        letter-spacing: -0.5px;
    }
    
    /* Card Setup & Zoom Effect */
    .mountain-card {
        border-radius: 20px !important;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
    .mountain-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(25, 135, 84, 0.12) !important;
    }
    .img-zoom-container {
        position: relative;
        height: 240px; 
        overflow: hidden; 
        background-color: #f8f9fa;
    }
    .img-zoom-container img {
        transition: transform 0.5s ease;
    }
    .mountain-card:hover .img-zoom-container img {
        transform: scale(1.06);
    }

    /* Floating Elevation Badge */
    .elevation-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(15, 23, 42, 0.75);
        backdrop-filter: blur(8px);
        color: #ffffff;
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        z-index: 10;
        border: 1px solid rgba(255, 255, 255, 0.15);
    }

    /* Table & Modal Cleanups */
    .table-responsive {
        border-radius: 12px;
        overflow: hidden;
    }
    .info-mini-card {
        background-color: #f8fafc;
        border-radius: 12px;
        padding: 12px 16px;
        border: 1px solid #e2e8f0;
    }
</style>

<div class="container-fluid p-4" style="background-color: #f8f9fa; min-height: 100vh;">
    {{-- Header Halaman --}}
    <div class="d-flex align-items-center mb-4 gap-2">
        <div class="p-2 bg-success text-white rounded-3 shadow-sm d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            <i class="bi bi-tree-fill fs-5"></i>
        </div>
        <h1 class="h3 mb-0 text-gray-800 fw-bold page-title-gradient">Profil Gunung</h1>
    </div>

    <div class="row">
        @forelse($gunung as $item)
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 mountain-card">
                    
                    {{-- Gambar Sampul dengan Floating Badge MDPL --}}
                    <div class="img-zoom-container">
                        <span class="elevation-badge">
                            <i class="bi bi-cloud-snow-fill me-1 text-info"></i>{{ number_format($item->ketinggian) }} MDPL
                        </span>
                        
                        @php
                            $fileUtama = is_array($item->gambar) && count($item->gambar) > 0 ? $item->gambar[0] : null;
                        @endphp
                        @if($fileUtama)
                            <img src="{{ asset('storage/' . $fileUtama) }}" class="card-img-top h-100 w-100" style="object-fit: cover;" onerror="this.src='https://placehold.co/600x400?text=Gambar+Error';">
                        @else
                            <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                <i class="bi bi-image fs-1 opacity-50"></i>
                            </div>
                        @endif
                    </div>

                    {{-- Konten Utama Card --}}
                    <div class="card-body p-4 d-flex flex-column justify-content-between text-center">
                        <div>
                            <h5 class="fw-bold text-dark mb-1" style="letter-spacing: -0.3px;">{{ $item->nama_gunung }}</h5>
                            <p class="text-secondary small mb-4">
                                <i class="bi bi-geo-alt-fill me-1 text-danger"></i> {{ $item->lokasi }}
                            </p>
                        </div>
                        
                        <div class="d-grid">
                            <button type="button" class="btn btn-success py-2½ shadow-sm d-flex align-items-center justify-content-center gap-2" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $item->id }}" style="border-radius: 12px; font-weight: 600; transition: 0.2s;">
                                <i class="bi bi-binoculars-fill"></i> Eksplor Jalur Detail
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MODAL DETAIL GUNUNG --}}
            <div class="modal fade" id="modalDetail{{ $item->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg" style="border-radius: 24px; overflow: hidden;">
                        
                        {{-- Carousel Gambar Atas Penuh --}}
                        <div class="position-relative">
                            @if(is_array($item->gambar) && count($item->gambar) > 0)
                                <div id="carouselGunung{{ $item->id }}" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner" style="height: 380px;">
                                        @foreach($item->gambar as $index => $img)
                                            <div class="carousel-item h-100 {{ $index == 0 ? 'active' : '' }}">
                                                <img src="{{ asset('storage/' . $img) }}" class="d-block w-100 h-100" style="object-fit: cover;">
                                            </div>
                                        @endforeach
                                    </div>
                                    @if(count($item->gambar) > 1)
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselGunung{{ $item->id }}" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#carouselGunung{{ $item->id }}" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
                                    @endif
                                </div>
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="bi bi-image text-muted fs-1"></i>
                                </div>
                            @endif
                            
                            {{-- Tombol Tutup Melayang di Atas Gambar Carousel --}}
                            <button type="button" class="btn-close btn-close-white p-3 position-absolute" data-bs-dismiss="modal" aria-label="Close" style="top: 20px; right: 20px; z-index: 20; background-color: rgba(15,23,42,0.5); border-radius: 50%;"></button>
                        </div>

                        {{-- Isi Konten Modal --}}
                        <div class="modal-body p-4 pt-4">
                            <div class="row align-items-center mb-3">
                                <div class="col-md-8">
                                    <h3 class="fw-bold text-success mb-1">{{ $item->nama_gunung }}</h3>
                                    <p class="text-muted mb-0 small"><i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $item->lokasi }}</p>
                                </div>
                                <div class="col-md-4 text-md-end mt-2 mt-md-0">
                                    <span class="badge bg-dark fs-6 px-3 py-2" style="border-radius: 10px;">
                                        {{ number_format($item->ketinggian) }} MDPL
                                    </span>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <div class="info-mini-card">
                                        <h6 class="fw-bold text-dark mb-2"><i class="bi bi-card-text text-success me-2"></i>Tentang Gunung</h6>
                                        <div class="text-secondary small" style="text-align: justify; line-height: 1.6;">
                                            {!! nl2br(e($item->deskripsi)) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Daftar Jalur Sinkron SPK --}}
                            <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                                <i class="bi bi-signpost-split-fill text-success"></i> Jalur Pendakian Resmi & Estimasi Waktu
                            </h6>
                            
                            <div class="table-responsive border shadow-sm">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr class="small text-uppercase tracking-wider">
                                            <th class="px-4 py-3" style="color: #475569;">Nama Jalur</th>
                                            <th class="py-3" style="color: #475569;">Tingkat Kesulitan</th>
                                            <th class="text-end px-4 py-3" style="color: #475569;">Estimasi Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($item->jalurs as $jalur)
                                            <tr>
                                                <td class="px-4 fw-bold text-dark">{{ $jalur->nama_jalur }}</td>
                                                <td>
                                                    @php
                                                        $diff = strtolower($jalur->tingkat_kesulitan ?? 'sedang');
                                                        $badgeClass = 'bg-info-subtle text-info border-info-subtle';
                                                        if(str_contains($diff, 'mudah')) $badgeClass = 'bg-success-subtle text-success border-success-subtle';
                                                        if(str_contains($diff, 'sulit') || str_contains($diff, 'keras')) $badgeClass = 'bg-danger-subtle text-danger border-danger-subtle';
                                                    @endphp
                                                    <span class="badge {{ $badgeClass }} px-2.5 py-1.5 border" style="font-weight: 600;">
                                                        {{ $jalur->tingkat_kesulitan ?? 'Sedang' }}
                                                    </span>
                                                </td>
                                                <td class="text-end px-4">
                                                    <span class="fw-bold text-primary bg-primary-subtle px-3 py-1 rounded-pill small border border-primary-subtle">
                                                        <i class="bi bi-clock-history me-1"></i>{{ $jalur->estimasi_jam ?? '-' }} Jam
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted small py-4">
                                                    <i class="bi bi-exclamation-circle me-1"></i> Data opsi jalur resmi belum diinput.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="modal-footer bg-light border-0 px-4 py-3">
                            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal" style="border-radius: 10px; font-weight: 600;">Kembali</button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 card border-0 shadow-sm" style="border-radius: 15px;">
                <i class="bi bi-folder-x display-3 text-muted opacity-50 mb-3"></i>
                <h5 class="text-muted fw-bold">Belum ada pangkalan data profil gunung.</h5>
            </div>
        @endforelse
    </div>
</div>

<style>
    /* Utility pendukung sub-warna dinamis Bootstrap 5 */
    .bg-success-subtle { background-color: #e8f5e9 !important; border-color: #c8e6c9 !important; }
    .bg-info-subtle { background-color: #e3f2fd !important; border-color: #bbdefb !important; }
    .bg-danger-subtle { background-color: #ffebee !important; border-color: #ffcdd2 !important; }
    .bg-primary-subtle { background-color: #eff6ff !important; border-color: #dbeafe !important; }
    .text-success { color: #157347 !important; }
    .text-info { color: #0284c7 !important; }
    .text-danger { color: #dc3545 !important; }
    .text-primary { color: #2563eb !important; }
</style>
@endsection