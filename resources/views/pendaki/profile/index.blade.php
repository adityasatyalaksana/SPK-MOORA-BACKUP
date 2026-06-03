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
        border-radius: 24px !important;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        border: 1px solid rgba(226, 232, 240, 0.8) !important;
        background: #ffffff;
    }
    .mountain-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 32px rgba(25, 135, 84, 0.12) !important;
        border-color: #198754 !important;
    }
    .img-zoom-container {
        position: relative;
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
        left: 15px;
        background: rgba(15, 23, 42, 0.8);
        backdrop-filter: blur(8px);
        color: #ffffff;
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 0.75rem;
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
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 mountain-card">
                    <div class="row g-0 h-100">
                        
                        {{-- Left Half: Image --}}
                        <div class="col-md-5 position-relative">
                            <div class="img-zoom-container h-100" style="min-height: 280px;">
                                <span class="elevation-badge">
                                    <i class="bi bi-cloud-snow-fill me-1 text-info"></i>{{ number_format($item->ketinggian) }} MDPL
                                </span>
                                
                                @php
                                    $fileUtama = is_array($item->gambar) && count($item->gambar) > 0 ? $item->gambar[0] : null;
                                @endphp
                                @if($fileUtama)
                                    <img src="{{ asset('storage/' . $fileUtama) }}" class="w-100 h-100" style="object-fit: cover;" onerror="this.src='https://placehold.co/600x400?text=Gambar+Error';">
                                @else
                                    <div class="d-flex align-items-center justify-content-center h-100 text-muted bg-light">
                                        <i class="bi bi-image fs-1 opacity-20"></i>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Right Half: Details --}}
                        <div class="col-md-7 d-flex flex-column justify-content-between p-4">
                            <div>
                                <h5 class="fw-bold text-dark mb-1" style="letter-spacing: -0.3px;">{{ $item->nama_gunung }}</h5>
                                <p class="text-secondary small mb-3">
                                    <i class="bi bi-geo-alt-fill me-1 text-danger"></i> {{ $item->lokasi }}
                                </p>
                                <p class="text-muted small mb-0" style="line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden; text-align: justify;">
                                    {{ $item->deskripsi ?? 'Belum ada deskripsi profil untuk gunung ini.' }}
                                </p>
                            </div>
                            
                            <div class="d-flex align-items-center justify-content-between pt-3 border-top mt-3">
                                <span class="small text-secondary fw-semibold">
                                    <i class="bi bi-signpost-split text-success me-1"></i> {{ $item->jalurs->count() }} Jalur Resmi
                                </span>
                                <button type="button" class="btn btn-success btn-sm px-3 py-2 shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $item->id }}" style="border-radius: 10px; font-weight: 600;">
                                    <i class="bi bi-binoculars-fill"></i> Eksplor Jalur Detail
                                </button>
                            </div>
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
                                    <div class="carousel-inner" style="height: 450px;">
                                        @foreach($item->gambar as $index => $img)
                                            <div class="carousel-item h-100 {{ $index == 0 ? 'active' : '' }} position-relative">
                                                <img src="{{ asset('storage/' . $img) }}" class="d-block w-100 h-100" style="object-fit: contain; background-color: #0f172a;">
                                                <a href="{{ asset('storage/' . $img) }}" target="_blank" class="btn btn-dark btn-sm position-absolute d-flex align-items-center gap-2" style="bottom: 20px; right: 20px; z-index: 30; background-color: rgba(15,23,42,0.85); border: 1px solid rgba(255,255,255,0.25); border-radius: 10px; padding: 6px 14px; font-weight: 600; font-size: 0.8rem; backdrop-filter: blur(4px);">
                                                    <i class="bi bi-zoom-in"></i> Perbesar Gambar
                                                </a>
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
                                     <h3 class="fw-bold text-success mb-1" style="letter-spacing: -0.5px;">{{ $item->nama_gunung }}</h3>
                                     <p class="text-muted mb-0 small"><i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $item->lokasi }}</p>
                                 </div>
                                 <div class="col-md-4 text-md-end mt-2 mt-md-0">
                                     <span class="badge bg-dark fs-6 px-3 py-2" style="border-radius: 10px; font-weight: 700; letter-spacing: 0.5px;">
                                         {{ number_format($item->ketinggian) }} MDPL
                                     </span>
                                 </div>
                             </div>

                             <div class="row g-3 mb-4">
                                 <div class="col-12">
                                     <div class="info-mini-card" style="background-color: #f0fdf4; border: 1px solid #dcfce7; border-radius: 16px; padding: 16px 20px;">
                                         <h6 class="fw-bold text-success mb-2"><i class="bi bi-card-text me-2"></i>Tentang Gunung</h6>
                                         <div class="text-secondary small" style="text-align: justify; line-height: 1.7; color: #374151 !important;">
                                             {!! nl2br(e($item->deskripsi)) !!}
                                         </div>
                                     </div>
                                 </div>
                             </div>

                             {{-- Daftar Jalur Sinkron SPK --}}
                             <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                                 <i class="bi bi-signpost-split-fill text-success"></i> Jalur Pendakian Resmi & Estimasi Waktu
                             </h6>
                             
                             <div class="table-responsive border-0 shadow-sm" style="border-radius: 16px; border: 1px solid #e2e8f0 !important; overflow: hidden;">
                                 <table class="table table-hover align-middle mb-0">
                                     <thead style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                                         <tr class="small text-uppercase tracking-wider">
                                             <th class="px-4 py-3" style="color: #475569; font-weight: 700;">Nama Jalur</th>
                                             <th class="py-3" style="color: #475569; font-weight: 700;">Tingkat Kesulitan</th>
                                             <th class="text-end px-4 py-3" style="color: #475569; font-weight: 700;">Estimasi Waktu</th>
                                         </tr>
                                     </thead>
                                     <tbody>
                                         @forelse($item->jalurs as $jalur)
                                             <tr>
                                                 <td class="px-4 fw-bold text-dark" style="font-size: 0.95rem;">{{ $jalur->nama_jalur }}</td>
                                                 <td>
                                                     @php
                                                         $diff = strtolower($jalur->tingkat_kesulitan ?? 'sedang');
                                                         $badgeClass = 'bg-warning-subtle text-warning border-warning-subtle';
                                                         if(str_contains($diff, 'mudah')) $badgeClass = 'bg-success-subtle text-success border-success-subtle';
                                                         if(str_contains($diff, 'sulit') || str_contains($diff, 'keras')) $badgeClass = 'bg-danger-subtle text-danger border-danger-subtle';
                                                     @endphp
                                                     <span class="badge {{ $badgeClass }} px-2.5 py-1.5 border" style="font-weight: 600; border-radius: 8px;">
                                                         {{ $jalur->tingkat_kesulitan ?? 'Sedang' }}
                                                     </span>
                                                 </td>
                                                 <td class="text-end px-4">
                                                     <span class="fw-bold text-primary bg-primary-subtle px-3 py-1.5 rounded-pill small border border-primary-subtle" style="font-size: 0.85rem;">
                                                         <i class="bi bi-clock-history me-1"></i>{{ $jalur->estimasi_jam ?? '-' }} Jam
                                                     </span>
                                                 </td>
                                             </tr>
                                         @empty
                                             <tr>
                                                 <td colspan="3" class="text-center text-muted small py-4">
                                                     <i class="bi bi-exclamation-circle me-1"></i> Data opsi jalur resmi belum dimasukkan.
                                                 </td>
                                             </tr>
                                         @endforelse
                                     </tbody>
                                 </table>
                             </div>
                         </div>
                         
                         <div class="modal-footer bg-light border-0 px-4 py-3">
                             <button type="button" class="btn btn-success px-4" data-bs-dismiss="modal" style="border-radius: 12px; font-weight: 600; background-color: #198754;">Tutup Detail</button>
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