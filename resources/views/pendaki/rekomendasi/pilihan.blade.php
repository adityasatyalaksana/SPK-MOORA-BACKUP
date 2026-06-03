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
                <button class="btn btn-light text-dark btn-edit-parameter ms-md-2 d-flex align-items-center justify-content-center shadow-sm" data-bs-toggle="collapse" data-bs-target="#quickEditCollapse" title="Ubah parameter pencarian">
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
                            <span class="fw-bold small">Rp {{ number_format($item->active_biaya_simaksi ?? $item->biaya_simaksi_weekday, 0, ',', '.') }}</span>
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
</script>
@endsection