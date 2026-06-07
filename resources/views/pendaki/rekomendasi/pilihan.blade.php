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

    /* Elegant Edit Button Styling (Icon Only) */
    .btn-edit-parameter {
        width: 38px;
        height: 38px;
        padding: 0 !important;
        border-radius: 12px;
        transition: all 0.2s ease;
        border: none;
    }
    .btn-edit-parameter:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 255, 255, 0.15);
    }

    /* Clean Dropdown Button Reset */
    #terminal-dropdown-btn {
        border-color: #ced4da !important;
        border-left: none !important;
        background-color: #ffffff !important;
        box-shadow: none !important;
        outline: none !important;
    }
    #terminal-dropdown-btn:focus, #terminal-dropdown-btn.open-dropdown {
        background-color: #ffffff !important;
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

    /* Premium Receipt Style Modal */
    .receipt-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 24px;
        position: relative;
        overflow: hidden;
    }
    .receipt-divider {
        display: flex;
        align-items: center;
        margin: 20px 0;
        position: relative;
    }
    .receipt-divider::before, .receipt-divider::after {
        content: '';
        width: 12px;
        height: 24px;
        background: #ffffff; /* Matches parent modal content background */
        display: block;
        border: 1px solid #e2e8f0;
        position: absolute;
        z-index: 2;
    }
    .receipt-divider::before {
        border-radius: 0 12px 12px 0;
        left: -25px; /* Adjusts to fit exactly overlapping the receipt-card border */
        border-left: none;
    }
    .receipt-divider::after {
        border-radius: 12px 0 0 12px;
        right: -25px; /* Adjusts to fit exactly overlapping the receipt-card border */
        border-right: none;
    }
    .receipt-line {
        flex-grow: 1;
        border-top: 2px dashed #cbd5e1;
    }
    .per-person-badge {
        background: linear-gradient(135deg, #198754 0%, #146c43 100%);
        color: white;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 8px 20px rgba(25, 135, 84, 0.15);
    }
    .receipt-item-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 8px;
        border-radius: 12px;
        transition: background-color 0.2s ease;
    }
    .receipt-item-row:hover {
        background-color: #f1f5f9;
    }
    .receipt-icon-wrapper {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        margin-right: 12px;
        flex-shrink: 0;
    }

    /* Print Stylesheet for Recommendations PDF */
    @media print {
        #sidebar, .top-navbar, .d-print-none, .btn-close, .modal, .modal-backdrop {
            display: none !important;
        }
        .wrapper {
            display: block !important;
            height: auto !important;
            min-height: 0 !important;
        }
        .main-container {
            display: block !important;
            height: auto !important;
            min-height: 0 !important;
            width: 100% !important;
        }
        #content {
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
        }
        body {
            background-color: #ffffff !important;
            font-size: 11pt;
            color: #000000 !important;
        }
        .search-summary-card {
            background: #f1f5f9 !important;
            border: 2px solid #cbd5e1 !important;
            color: #0f172a !important;
            padding: 20px !important;
            border-radius: 12px !important;
            box-shadow: none !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .search-summary-card h3, .search-summary-card p {
            color: #0f172a !important;
        }
        .parameter-badge {
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            color: #0f172a !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .hiking-card {
            box-shadow: none !important;
            border: 1px solid #cbd5e1 !important;
            break-inside: avoid !important;
            page-break-inside: avoid !important;
            margin-bottom: 20px !important;
            border-radius: 12px !important;
            padding: 15px !important;
        }
        .best-card {
            border: 2px solid #198754 !important;
        }
        .row {
            display: flex !important;
            flex-wrap: wrap !important;
        }
        .col-lg-4, .col-md-6 {
            flex: 0 0 100% !important;
            max-width: 100% !important;
            width: 100% !important;
        }
        .rank-floating {
            position: relative !important;
            top: 0 !important;
            left: 0 !important;
            display: inline-block !important;
            margin-bottom: 10px !important;
            border-radius: 6px !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .price-tag {
            background: #eefaf4 !important;
            color: #2d6a4f !important;
            border: 1px solid #a3cfbb !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .best-price {
            background: #198754 !important;
            color: white !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>

<div class="container py-5">
    {{-- Ringkasan Pencarian --}}
    <div class="card search-summary-card shadow-lg mb-4">
        <div class="row align-items-center">
            <div class="col-md-5">
                <h3 class="fw-bold mb-1">Eksplorasi Sesuai Kantong</h3>
                <p class="text-white-50 mb-0 small">Berdasarkan rute budget, jumlah anggota, beserta tanggal rencana keberangkatan Anda.</p>
            </div>
            <div class="col-md-7 mt-3 mt-md-0 d-flex flex-wrap gap-2 justify-content-md-end align-items-center">
                <div class="parameter-badge">
                    <i class="bi bi-wallet2 me-2"></i>Rp {{ number_format($input['budget'], 0, ',', '.') }}
                </div>
                <div class="parameter-badge">
                    <i class="bi bi-geo-alt me-2"></i>{{ $nama_terminal }}
                </div>
                <div class="parameter-badge">
                    <i class="bi bi-people me-2"></i>{{ $input['jumlah_anggota'] }} Orang
                </div>
                {{-- DETAIL INFORMASI TANGGAL PILIHAN USER --}}
                <div class="parameter-badge border-success border-opacity-50 fw-bold" style="background: rgba(25, 135, 84, 0.2);">
                    <i class="bi bi-calendar-event me-2 text-success"></i>{{ isset($input['tanggal_keberangkatan']) ? date('d M Y', strtotime($input['tanggal_keberangkatan'])) : '-' }}
                </div>
                
                {{-- Single Edit Button (Icon Only) --}}
                <button class="btn btn-light text-dark btn-edit-parameter ms-md-2 d-flex align-items-center justify-content-center shadow-sm d-print-none" data-bs-toggle="collapse" data-bs-target="#quickEditCollapse" title="Ubah parameter pencarian">
                    <i class="bi bi-pencil-square text-success fs-5"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- Quick Edit Collapse Form --}}
    <div class="collapse mb-5" id="quickEditCollapse">
        <div class="card p-4 border-0 shadow-sm rounded-4 bg-light">
            <div class="d-flex align-items-center mb-3">
                <div class="p-2 bg-success text-white rounded-3 d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <h6 class="fw-bold text-dark mb-0">Ubah Parameter Pencarian Rekomendasi</h6>
            </div>
            
            <form action="{{ route('rekomendasi.proses') }}" method="GET">
                <div class="row g-4">
                    {{-- Input 1: Budget Maksimal --}}
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Budget Maksimal Kelompok (Rp)</label>
                        <div class="input-group shadow-xs rounded-3 overflow-hidden">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-wallet2"></i></span>
                            <input type="hidden" name="budget" id="budget-hidden" value="{{ $input['budget'] }}">
                            <input type="text" id="budget-display" class="form-control border-start-0 py-2.5 fw-semibold" value="{{ number_format($input['budget'], 0, ',', '.') }}" placeholder="Contoh: 1.500.000" required>
                        </div>
                    </div>

                    {{-- Input 2: Jumlah Anggota --}}
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Jumlah Anggota (Orang)</label>
                        <div class="input-group shadow-xs rounded-3 overflow-hidden">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-people"></i></span>
                            <input type="number" name="jumlah_anggota" class="form-control border-start-0 py-2.5 fw-semibold" value="{{ $input['jumlah_anggota'] }}" min="1" placeholder="Contoh: 4" required>
                        </div>
                    </div>

                    {{-- Input 3: Terminal Keberangkatan (Custom Searchable Dropdown aligned in input-group) --}}
                    <div class="col-md-6 position-relative">
                        <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Terminal Keberangkatan Asal</label>
                        <input type="hidden" name="terminal_id" id="selected-terminal-id" value="{{ $input['terminal_id'] }}" required>
                        
                        <div class="input-group shadow-xs rounded-3 overflow-hidden">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-geo-alt"></i></span>
                            <button type="button" class="btn btn-white form-control border-start-0 py-2.5 text-start d-flex justify-content-between align-items-center" id="terminal-dropdown-btn">
                                @php
                                    $selectedTerminal = $terminals->firstWhere('id', $input['terminal_id']);
                                    $selectedTerminalName = $selectedTerminal ? $selectedTerminal->nama_terminal : '-- Pilih Terminal Asal Bus --';
                                @endphp
                                <span id="terminal-dropdown-label" class="fw-semibold text-dark">{{ $selectedTerminalName }}</span>
                                <i class="bi bi-chevron-down text-secondary"></i>
                            </button>
                        </div>
                        
                        <div class="custom-dropdown-menu d-none position-absolute bg-white shadow-lg border rounded-3 p-3 mt-1" id="terminal-dropdown-menu" style="z-index: 1050; left: 12px; right: 12px; max-height: 250px; overflow-y: auto;">
                            <input type="text" class="form-control mb-3 search-input" placeholder="Cari terminal keberangkatan...">
                            <div class="dropdown-list-items">
                                @foreach($terminals as $terminal)
                                    @php
                                        $cleanName = str_ireplace('Terminal ', '', $terminal->nama_terminal);
                                    @endphp
                                    <div class="dropdown-item-card p-3 mb-2 rounded-3 {{ $terminal->id == $input['terminal_id'] ? 'selected' : '' }}" 
                                         data-id="{{ $terminal->id }}" 
                                         data-search="{{ strtolower($cleanName) }} {{ strtolower($terminal->lokasi) }}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="fw-bold text-dark d-block"><i class="bi bi-geo-alt-fill text-danger me-2"></i>{{ $terminal->nama_terminal }}</span>
                                                <span class="text-muted small"><i class="bi bi-map me-1"></i>{{ $terminal->lokasi }}</span>
                                            </div>
                                            <span class="badge bg-primary-subtle text-primary">{{ $terminal->tipe }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Input 4: Tanggal Keberangkatan --}}
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Tanggal Rencana Keberangkatan</label>
                        <div class="input-group shadow-xs rounded-3 overflow-hidden">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-calendar-event"></i></span>
                            <input type="date" name="tanggal_keberangkatan" class="form-control border-start-0 py-2.5 fw-semibold" value="{{ $input['tanggal_keberangkatan'] }}" min="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex justify-content-end gap-3 mt-4 border-top pt-3">
                    <button type="button" class="btn btn-secondary px-4 py-2 fw-bold" style="border-radius: 8px;" data-bs-toggle="collapse" data-bs-target="#quickEditCollapse">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-success px-4 py-2 fw-bold" style="border-radius: 8px;">
                        <i class="bi bi-check-circle-fill me-1"></i> Terapkan Perubahan
                    </button>
                </div>
            </form>
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
                <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
                    <div class="modal-header border-0 pb-0 bg-white d-flex justify-content-between align-items-center pt-4 px-4">
                        <div class="d-flex align-items-center">
                            <div class="p-2 bg-success bg-opacity-10 text-success rounded-3 me-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                <i class="bi bi-wallet2 fs-5 text-success"></i>
                            </div>
                            <h5 class="modal-title fw-bold text-dark mb-0 ms-2">Rincian Estimasi Biaya</h5>
                        </div>
                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body p-4 bg-white">
                        <div class="receipt-card">
                            {{-- Card Biaya per Orang --}}
                            <div class="per-person-badge text-center mb-4">
                                <span class="small opacity-80 d-block mb-1 text-uppercase tracking-wider fw-semibold" style="font-size: 0.75rem;">Biaya per Orang</span>
                                <h3 class="fw-bold mb-0 text-white" style="font-weight: 800; font-size: 1.75rem;">Rp {{ number_format($item->biaya_per_orang, 0, ',', '.') }}</h3>
                            </div>
                            
                            {{-- Rincian Item --}}
                            <div class="d-flex flex-column gap-2">
                                <div class="receipt-item-row">
                                    <div class="d-flex align-items-center">
                                        <div class="receipt-icon-wrapper text-success bg-success bg-opacity-10">
                                            <i class="bi bi-bus-front-fill text-success"></i>
                                        </div>
                                        <div>
                                            <span class="fw-bold text-dark d-block" style="font-size: 0.85rem;">Transport PP</span>
                                            <span class="text-muted small d-block" style="font-size: 0.75rem;">Armada: {{ $item->nama_armada }}</span>
                                        </div>
                                    </div>
                                    <span class="fw-bold text-dark" style="font-size: 0.9rem;">Rp {{ number_format($item->harga_pp, 0, ',', '.') }}</span>
                                </div>
                                
                                <div class="receipt-item-row">
                                    <div class="d-flex align-items-center">
                                        <div class="receipt-icon-wrapper text-primary bg-primary bg-opacity-10">
                                            <i class="bi bi-ticket-perforated-fill text-primary"></i>
                                        </div>
                                        <div>
                                            <span class="fw-bold text-dark d-block" style="font-size: 0.85rem;">Simaksi & Perizinan</span>
                                            <span class="text-muted small d-block" style="font-size: 0.75rem;">Masuk jalur pendakian</span>
                                        </div>
                                    </div>
                                    <span class="fw-bold text-dark" style="font-size: 0.9rem;">Rp {{ number_format($item->active_biaya_simaksi ?? $item->biaya_simaksi_weekday, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            
                            {{-- Pembatas Sobekan Tiket --}}
                            <div class="receipt-divider">
                                <div class="receipt-line"></div>
                            </div>
                            
                            {{-- Total Dana Kelompok --}}
                            <div class="p-3 rounded-3 bg-success bg-opacity-10 border border-success border-opacity-20 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="receipt-icon-wrapper text-success bg-white border-0 me-2 shadow-sm" style="width: 32px; height: 32px; font-size: 0.95rem; margin-right: 8px;">
                                        <i class="bi bi-people-fill text-success"></i>
                                    </div>
                                    <div>
                                        <span class="text-success fw-bold d-block" style="font-size: 0.8rem; line-height: 1.2;">Total Kelompok</span>
                                        <span class="text-muted small d-block" style="font-size: 0.7rem;">Untuk {{ $input['jumlah_anggota'] }} Anggota</span>
                                    </div>
                                </div>
                                <h4 class="fw-bold text-success mb-0" style="font-weight: 800; font-size: 1.25rem;">Rp {{ number_format($item->total_dana_kelompok, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                        
                        <div class="text-center mt-3">
                            <span class="text-muted small" style="font-size: 0.7rem;">
                                <i class="bi bi-info-circle me-1 text-secondary"></i> Estimasi di atas berdasarkan harga tiket armada bus dan simaksi saat ini.
                            </span>
                        </div>

                        <button onclick="printReceipt('modalBiaya{{ $item->id }}', '{{ $item->gunung->nama_gunung }}', '{{ $item->nama_jalur }}', '{{ $item->gunung->lokasi }}', '{{ $item->gunung->ketinggian }}', '{{ $item->estimasi_jam }}', '{{ $item->nama_armada }}', '{{ $nama_terminal }}', '{{ $item->nama_terminal_tujuan }}', '{{ $item->estimasi_perjalanan }}')" class="btn btn-outline-success w-100 rounded-pill fw-bold py-2.5 mt-3 d-print-none" style="font-size: 0.85rem; border-width: 2px;">
                            <i class="bi bi-file-earmark-pdf-fill me-2"></i>Cetak Rincian Biaya (PDF)
                        </button>
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
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Budget Display Thousand Separator Formatter
        const budgetDisplay = document.getElementById('budget-display');
        const budgetHidden = document.getElementById('budget-hidden');

        if (budgetDisplay && budgetHidden) {
            function formatNumber(num) {
                return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            function updateValues() {
                let rawValue = budgetDisplay.value.replace(/\D/g, '');
                budgetHidden.value = rawValue;
                
                if (rawValue) {
                    budgetDisplay.value = formatNumber(rawValue);
                } else {
                    budgetDisplay.value = '';
                }
            }

            budgetDisplay.addEventListener('input', updateValues);
        }

        // 2. Custom Dropdown Terminal
        const termBtn = document.getElementById('terminal-dropdown-btn');
        const termLabel = document.getElementById('terminal-dropdown-label');
        const termMenu = document.getElementById('terminal-dropdown-menu');
        const termHiddenInput = document.getElementById('selected-terminal-id');
        const termSearchInput = termMenu.querySelector('.search-input');
        const termItems = termMenu.querySelectorAll('.dropdown-item-card');

        if (termBtn && termMenu) {
            // Toggle menu visibility
            termBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                termMenu.classList.toggle('d-none');
                termBtn.classList.toggle('open-dropdown');
                if (!termMenu.classList.contains('d-none')) {
                    termSearchInput.focus();
                }
            });

            // Hide menu when clicking outside
            document.addEventListener('click', function(e) {
                if (!termBtn.contains(e.target) && !termMenu.contains(e.target)) {
                    termMenu.classList.add('d-none');
                    termBtn.classList.remove('open-dropdown');
                }
            });

            // Handle item selection
            termItems.forEach(function(item) {
                item.addEventListener('click', function() {
                    // Clear selection
                    termItems.forEach(i => i.classList.remove('selected'));
                    
                    // Set selected state
                    item.classList.add('selected');
                    
                    // Set hidden input value
                    termHiddenInput.value = item.getAttribute('data-id');
                    
                    // Update button text
                    const titleText = item.querySelector('.fw-bold').textContent.trim();
                    termLabel.innerHTML = `<strong>${titleText}</strong>`;
                    termLabel.classList.remove('text-muted');
                    termLabel.classList.add('text-dark');

                    // Close menu
                    termMenu.classList.add('d-none');
                    termBtn.classList.remove('open-dropdown');
                });
            });

            // Handle search filtering
            termSearchInput.addEventListener('input', function() {
                const query = termSearchInput.value.toLowerCase().trim();
                termItems.forEach(function(item) {
                    const searchData = item.getAttribute('data-search');
                    if (searchData.includes(query)) {
                        item.style.setProperty('display', 'block', 'important');
                    } else {
                        item.style.setProperty('display', 'none', 'important');
                    }
                });
            });

            // Form Submit Validation for custom dropdown
            const form = termBtn.closest('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (!termHiddenInput.value) {
                        e.preventDefault();
                        alert('Silakan pilih Terminal Keberangkatan Asal terlebih dahulu.');
                        termBtn.focus();
                        termBtn.click();
                    }
                });
            }
        }
    });

    function printReceipt(modalId, mountName, jalName, lokasi, ketinggian, estimasiJam, namaArmada, terminalAsal, terminalTujuan, estimasiBus) {
        const modalEl = document.getElementById(modalId);
        if (!modalEl) return;
        
        const receiptCard = modalEl.querySelector('.receipt-card');
        if (!receiptCard) return;

        const printWindow = window.open('', '_blank', 'width=700,height=900');
        
        let stylesHtml = '';
        document.querySelectorAll('link[rel="stylesheet"], style').forEach(node => {
            stylesHtml += node.outerHTML;
        });
        
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Detail Rekomendasi - ${mountName}</title>
                ${stylesHtml}
                <style>
                    body {
                        background-color: #ffffff !important;
                        padding: 15px !important;
                        font-family: 'Inter', system-ui, -apple-system, sans-serif;
                        font-size: 10pt;
                    }
                    .print-container {
                        width: 100%;
                        max-width: 600px;
                        margin: 0 auto;
                        page-break-inside: avoid !important;
                        break-inside: avoid !important;
                    }
                    .print-header {
                        text-align: center;
                        margin-bottom: 12px;
                        border-bottom: 2px double #cbd5e1;
                        padding-bottom: 8px;
                    }
                    .print-header h4 {
                        font-size: 1.15rem !important;
                        margin-bottom: 2px !important;
                    }
                    .print-section-title {
                        font-size: 0.7rem;
                        font-weight: 700;
                        text-transform: uppercase;
                        letter-spacing: 0.8px;
                        color: #0f172a;
                        margin-top: 12px;
                        margin-bottom: 6px;
                        border-bottom: 1.5px solid #e2e8f0;
                        padding-bottom: 3px;
                        display: flex;
                        align-items: center;
                        gap: 6px;
                    }
                    .detail-grid {
                        display: grid;
                        grid-template-columns: 1fr 1fr;
                        gap: 8px;
                        margin-bottom: 8px;
                    }
                    .detail-item {
                        background-color: #f8fafc;
                        border: 1px solid #f1f5f9;
                        padding: 6px 10px;
                        border-radius: 8px;
                    }
                    .detail-label {
                        font-size: 0.55rem;
                        color: #64748b;
                        text-transform: uppercase;
                        font-weight: 700;
                        display: block;
                        margin-bottom: 1px;
                    }
                    .detail-value {
                        font-size: 0.75rem;
                        font-weight: 700;
                        color: #1e293b;
                    }
                    .receipt-card {
                        width: 100% !important;
                        box-shadow: none !important;
                        border: 1px solid #e2e8f0 !important;
                        background-color: #f8fafc !important;
                        padding: 12px !important;
                        border-radius: 12px !important;
                    }
                    .per-person-badge {
                        padding: 10px !important;
                        border-radius: 10px !important;
                        margin-bottom: 12px !important;
                    }
                    .per-person-badge h3 {
                        font-size: 1.25rem !important;
                    }
                    .receipt-item-row {
                        padding: 4px 6px !important;
                    }
                    .receipt-divider {
                        margin: 10px 0 !important;
                    }
                    .receipt-divider::before, .receipt-divider::after {
                        height: 16px !important;
                        width: 8px !important;
                    }
                    .receipt-divider::before {
                        left: -13px !important;
                        border-radius: 0 8px 8px 0 !important;
                    }
                    .receipt-divider::after {
                        right: -13px !important;
                        border-radius: 8px 0 0 8px !important;
                    }
                    .receipt-icon-wrapper {
                        width: 30px !important;
                        height: 30px !important;
                        font-size: 0.9rem !important;
                        margin-right: 8px !important;
                    }
                    .text-success-emphasis h4 {
                        font-size: 1.1rem !important;
                    }
                    .receipt-divider::before, .receipt-divider::after {
                        background: #ffffff !important;
                    }
                    * {
                        -webkit-print-color-adjust: exact !important;
                        print-color-adjust: exact !important;
                    }
                    @page {
                        size: A4;
                        margin: 10mm;
                    }
                </style>
            </head>
            <body>
                <div class="print-container">
                    <div class="print-header">
                        <h4 class="fw-bold mb-1" style="color: #0f172a; letter-spacing: 0.5px;">DETAIL REKOMENDASI PENDAKIAN</h4>
                        <p class="text-muted small mb-0">Sistem Pendukung Keputusan SPK-MOORA</p>
                    </div>

                    <div class="print-section-title">
                        <i class="bi bi-mountains-fill text-success"></i> Informasi Gunung & Jalur
                    </div>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <span class="detail-label">Nama Gunung</span>
                            <span class="detail-value">Gn. ${mountName}</span>
                          </div>
                          <div class="detail-item">
                              <span class="detail-label">Jalur Pendakian</span>
                              <span class="detail-value">${jalName}</span>
                          </div>
                          <div class="detail-item">
                              <span class="detail-label">Lokasi Gunung</span>
                              <span class="detail-value">${lokasi}</span>
                          </div>
                          <div class="detail-item">
                              <span class="detail-label">Ketinggian / Estimasi</span>
                              <span class="detail-value">${ketinggian} MDPL / ± ${estimasiJam} Jam</span>
                          </div>
                      </div>

                      <div class="print-section-title">
                          <i class="bi bi-bus-front-fill text-primary"></i> Transportasi & Perjalanan Bus
                      </div>
                      <div class="detail-grid">
                          <div class="detail-item">
                              <span class="detail-label">Armada Bus</span>
                              <span class="detail-value">${namaArmada}</span>
                          </div>
                          <div class="detail-item">
                              <span class="detail-label">Terminal Asal</span>
                              <span class="detail-value">${terminalAsal}</span>
                          </div>
                          <div class="detail-item">
                              <span class="detail-label">Terminal Tujuan</span>
                              <span class="detail-value">${terminalTujuan}</span>
                          </div>
                          <div class="detail-item">
                              <span class="detail-label">Estimasi Durasi Bus</span>
                              <span class="detail-value">± ${estimasiBus} Jam</span>
                          </div>
                      </div>

                      <div class="print-section-title">
                          <i class="bi bi-wallet2 text-success"></i> Rincian Estimasi Biaya
                      </div>
                      ${receiptCard.outerHTML}

                      <div class="text-center mt-5 text-muted small" style="font-size: 0.7rem; border-top: 1px dashed #cbd5e1; padding-top: 15px;">
                          Dokumen estimasi ini dicetak otomatis oleh sistem SPK-MOORA pada: ${new Date().toLocaleString('id-ID')}
                      </div>
                  </div>
                  <script>
                      window.onload = function() {
                          window.print();
                          setTimeout(function() {
                              window.close();
                          }, 500);
                      };
                  <\/script>
              </body>
              </html>
          `);
          
          printWindow.document.close();
      }
  </script>
@endsection