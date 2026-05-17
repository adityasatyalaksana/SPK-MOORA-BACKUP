@extends('layouts.admin')

@section('content')
<style>
    /* Header Card Style */
    .search-summary-card { 
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%); 
        border-radius: 20px; color: white; padding: 30px; margin-bottom: 50px; border: none; 
    }
    .parameter-badge { 
        background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); 
        padding: 8px 15px; border-radius: 12px; font-size: 0.85rem; 
    }

    /* Hiking Card Style */
    .hiking-card { 
        border: none; border-radius: 25px; overflow: visible !important; 
        background: #ffffff; transition: transform 0.3s ease; position: relative; 
    }
    .hiking-card:hover { transform: translateY(-10px); box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important; }
    
    /* Ranking Badge - Solusi agar tidak tertutup */
    .rank-floating {
        position: absolute; top: -18px; left: 20px; z-index: 100;
        padding: 6px 16px; border-radius: 50px; font-weight: 800;
        font-size: 0.75rem; color: white; box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        display: flex; align-items: center;
    }
    .rank-utama { background: #198754; border: 2px solid #ffffff; }
    .rank-biasa { background: #6c757d; border: 2px solid #ffffff; }

    .badge-difficulty { position: absolute; top: 20px; right: 20px; padding: 6px 12px; border-radius: 50px; font-weight: 700; font-size: 0.7rem; text-transform: uppercase; }
    .mount-info-header { background: #f8f9fa; padding: 15px; border-radius: 20px; margin-bottom: 20px; }
    .info-label { font-size: 0.65rem; color: #adb5bd; text-transform: uppercase; font-weight: 700; display: block; }
    .info-value { font-weight: 700; color: #2d3436; font-size: 0.85rem; }
    
    /* Box Transportasi */
    .transport-box { padding: 0 5px; margin-bottom: 25px; flex-grow: 1; }
    .transport-item { display: flex; align-items: center; margin-bottom: 12px; }
    
    .price-tag { background: #eefaf4; color: #2d6a4f; padding: 15px; border-radius: 15px; text-align: center; }
    .best-card { border: 2px solid #198754 !important; }
    .best-price { background: #198754 !important; color: white !important; }
</style>

<div class="container py-5">
    {{-- Ringkasan Pencarian --}}
    <div class="card search-summary-card shadow-lg">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h3 class="fw-bold mb-1">Eksplorasi Sesuai Kantong</h3>
                <p class="text-white-50 mb-0 small">Berdasarkan rute budget, jumlah anggota, beserta tanggal rencana keberangkatan Anda.</p>
            </div>
            <div class="col-md-6 mt-3 mt-md-0 d-flex flex-wrap gap-2 justify-content-md-end">
                <div class="parameter-badge">
                    <i class="bi bi-wallet2 me-2"></i>Rp {{ number_format($input['budget'], 0, ',', '.') }}
                </div>
                <div class="parameter-badge">
                    <i class="bi bi-geo-alt me-2"></i>{{ $nama_terminal }}
                </div>
                <div class="parameter-badge">
                    <i class="bi bi-people me-2"></i>{{ $input['jumlah_anggota'] }} Orang
                </div>
                {{-- DETAIL INFORMASI TANGGAL PILIHAN USER (FITUR BARU) --}}
                <div class="parameter-badge border-success border-opacity-50 fw-bold" style="background: rgba(25, 135, 84, 0.2);">
                    <i class="bi bi-calendar-event me-2 text-success"></i>{{ isset($input['tanggal_keberangkatan']) ? date('d M Y', strtotime($input['tanggal_keberangkatan'])) : '-' }}
                </div>
            </div>
        </div>
    </div>

    <div class="row g-5">
        @forelse($rekomendasi as $item)
        <div class="col-lg-4 col-md-6">
            <div class="card hiking-card shadow-sm p-3 h-100 {{ $loop->first ? 'best-card' : '' }}">
                
                {{-- Badge Ranking --}}
                <div class="rank-floating {{ $loop->first ? 'rank-utama' : 'rank-biasa' }}">
                    @if($loop->first)
                        <i class="bi bi-trophy-fill me-2"></i>REKOMENDASI UTAMA
                    @else
                        RANKING {{ $loop->iteration }}
                    @endif
                </div>

                <span class="badge-difficulty {{ $item->tingkat_kesulitan == 'Mudah' ? 'bg-success text-white' : ($item->tingkat_kesulitan == 'Sedang' ? 'bg-warning text-dark' : 'bg-danger text-white') }}">
                    {{ $item->tingkat_kesulitan }}
                </span>

                <div class="card-body d-flex flex-column pt-4">
                    <div class="mb-4">
                        <h4 class="fw-bold mb-0">{{ $item->gunung->nama_gunung }}</h4>
                        <span class="text-muted small">
                            {{ $item->gunung->lokasi }} | {{ $item->gunung->ketinggian }} Mdpl
                        </span>
                    </div>

                    <div class="mount-info-header d-flex justify-content-between text-center">
                        <div class="flex-fill border-end">
                            <span class="info-label">Jalur</span>
                            <span class="info-value">{{ $item->nama_jalur }}</span>
                        </div>
                        <div class="flex-fill border-end px-2">
                            <span class="info-label">Waktu</span>
                            <span class="info-value">{{ $item->estimasi_jam }} Jam</span>
                        </div>
                        <div class="flex-fill">
                            <span class="info-label">Bus</span>
                            <span class="info-value text-primary">{{ $item->nama_armada }}</span>
                        </div>
                    </div>

                    {{-- Bagian Transportasi dengan Terminal Terpilih --}}
                    <div class="transport-box">
                        <div class="transport-item">
                            <i class="bi bi-record-circle text-success me-3"></i>
                            <div>
                                <span class="info-label">Dari</span>
                                <span class="info-value">{{ $nama_terminal }}</span>
                            </div>
                        </div>
                        <div class="transport-item">
                            <i class="bi bi-geo-alt-fill text-danger me-3"></i>
                            <div>
                                <span class="info-label">Tujuan</span>
                                <span class="info-value">{{ $item->nama_terminal_tujuan }}</span>
                            </div>
                        </div>
                        <div class="transport-item">
                            <i class="bi bi-clock-history text-muted me-3"></i>
                            <div>
                                <span class="info-label">Durasi Perjalanan Bus</span>
                                <span class="info-value">± {{ $item->estimasi_perjalanan }} Jam</span>
                            </div>
                        </div>
                    </div>

                    <div class="price-tag mb-3 {{ $loop->first ? 'best-price' : '' }}">
                        <span class="small d-block mb-1 opacity-75">Total Estimasi Kelompok</span>
                        <h4 class="fw-bold mb-0">Rp {{ number_format($item->total_dana_kelompok, 0, ',', '.') }}</h4>
                    </div>

                    <button 
                        class="btn {{ $loop->first ? 'btn-success' : 'btn-dark' }} w-100 rounded-pill fw-bold py-2" 
                        data-bs-toggle="modal" 
                        data-bs-target="#modalBiaya{{ $item->id }}">
                        Lihat Rincian Biaya
                    </button>
                </div>
            </div>
        </div>

        {{-- Modal Rincian --}}
        <div class="modal fade" id="modalBiaya{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 rounded-4 shadow">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="fw-bold">Rincian Estimasi Biaya</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4 text-center">
                        <div class="p-3 rounded-4 bg-light mb-3">
                            <span class="small text-muted d-block">Biaya per Orang</span>
                            <h4 class="fw-bold text-dark mb-0">Rp {{ number_format($item->biaya_per_orang, 0, ',', '.') }}</h4>
                        </div>
                        <div class="d-flex justify-content-between px-2 mb-2">
                            <span class="text-muted small">Transport PP ({{ $item->nama_armada }})</span>
                            <span class="fw-bold small">Rp {{ number_format($item->harga_pp, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between px-2 mb-4">
                            <span class="text-muted small">Simaksi & Perizinan</span>
                            <span class="fw-bold small">Rp {{ number_format($item->biaya_simaksi, 0, ',', '.') }}</span>
                        </div>
                        <hr>
                        <span class="small text-muted d-block mb-1">Total Dana Kelompok ({{ $input['jumlah_anggota'] }} Orang)</span>
                        <h3 class="fw-bold text-success mb-0">Rp {{ number_format($item->total_dana_kelompok, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <div class="p-5 bg-white rounded-4 shadow-sm border">
                <i class="bi bi-emoji-frown display-4 text-muted mb-3 d-block"></i>
                <h4 class="fw-bold">Maaf, Jalur Tidak Ditemukan</h4>
                <p class="text-muted">Coba sesuaikan budget atau pilih terminal keberangkatan lain.</p>
            </div>
        </div>
        @endforelse
    </div>

    <div class="text-center mt-5">
        <a href="{{ route('rekomendasi.index') }}" class="btn btn-outline-dark rounded-pill px-5 py-2 fw-bold">
            <i class="bi bi-arrow-left me-2"></i>Cari Budget Lain
        </a>
    </div>
</div>
@endsection