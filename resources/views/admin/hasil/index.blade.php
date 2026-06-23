@extends('layouts.admin')

@section('content')
@php
    // Mapping of alternative key to academic code A1, A2, ...
    $altLabels = [];
    $idx = 1;
    foreach($alternatifs as $altKey => $alt) {
        $altLabels[$altKey] = 'A' . $idx++;
    }

    // Benefit criteria codes string (e.g., C3+C4)
    $benefitCodes = implode('+', $kriterias->filter(fn($k) => strtolower($k->tipe) == 'benefit')->pluck('kode_kriteria')->toArray());
    
    // Cost criteria codes string (e.g., C1+C2+C5+C6)
    $costCodes = implode('+', $kriterias->filter(fn($k) => strtolower($k->tipe) != 'benefit')->pluck('kode_kriteria')->toArray());

    $totalAlternatifs = count($alternatifs);
    $totalKriterias = count($kriterias);
    $topAlternativeName = '-';
    $topAlternativeSub = '';
    $topAlternativeSkor = '0.00000';
    if (!empty($hasil)) {
        $firstRank = $hasil[0];
        $topAlternativeName = "Gn. " . $firstRank['nama_gunung'] . " (" . $firstRank['nama_jalur'] . ")";
        $topAlternativeSub = $firstRank['nama_armada'] . " (" . $firstRank['start_terminal'] . " → " . $firstRank['end_terminal'] . ")";
        $topAlternativeSkor = number_format($firstRank['skor'], 5, ',', '.');
    }
@endphp

<style>
    /* Premium style elements for MOORA results page */
    .hasil-header {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        border-radius: 16px;
        color: #ffffff;
    }
    .accordion-button-custom {
        background-color: #ffffff;
        color: #1e293b;
        font-weight: 700;
        border: 1px solid #e2e8f0;
        border-radius: 12px !important;
        transition: all 0.2s ease;
    }
    .accordion-button-custom:not(.collapsed) {
        background-color: #f8fafc;
        color: #3b82f6;
        box-shadow: none;
        border-color: #3b82f6;
    }
    .step-badge {
        width: 32px;
        height: 32px;
        background-color: #3b82f6;
        color: #ffffff;
        font-weight: 700;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        font-size: 0.9rem;
    }
    .accordion-button-custom.collapsed .step-badge {
        background-color: #64748b;
    }
    .rank-gold {
        background: linear-gradient(135deg, #fef08a 0%, #facc15 100%);
        color: #854d0e;
        border: 1px solid #eab308;
    }
    .rank-silver {
        background: linear-gradient(135deg, #f1f5f9 0%, #cbd5e1 100%);
        color: #334155;
        border: 1px solid #94a3b8;
    }
    .rank-bronze {
        background: linear-gradient(135deg, #ffedd5 0%, #fdba74 100%);
        color: #9a3412;
        border: 1px solid #f97316;
    }
    .rank-normal {
        background-color: #f8fafc;
        color: #64748b;
        border: 1px solid #cbd5e1;
    }

    /* Brackets for Matrix X */
    .matrix-scrollable {
        overflow-x: auto;
        max-width: 100%;
        padding: 10px 0;
    }
    .matrix-wrapper {
        display: inline-flex;
        align-items: center;
        margin: 1rem 0;
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 24px;
    }
    .matrix-label {
        font-family: 'Outfit', 'Inter', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #0f172a;
        margin-right: 16px;
    }
    .matrix-brackets-container {
        display: inline-flex;
        align-items: stretch;
    }
    .matrix-bracket-left {
        width: 16px;
        border-top: 3.5px solid #334155;
        border-left: 3.5px solid #334155;
        border-bottom: 3.5px solid #334155;
        border-radius: 6px 0 0 6px;
        margin-right: 16px;
    }
    .matrix-bracket-right {
        width: 16px;
        border-top: 3.5px solid #334155;
        border-right: 3.5px solid #334155;
        border-bottom: 3.5px solid #334155;
        border-radius: 0 6px 6px 0;
        margin-left: 16px;
    }
    .matrix-grid {
        display: grid;
        grid-gap: 12px 28px;
        text-align: center;
        font-family: 'Courier New', Courier, monospace;
        font-weight: 700;
        font-size: 1.15rem;
        color: #0f172a;
        padding: 6px 0;
        align-items: center;
    }
    .math-formula-block {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 14px 18px;
        font-family: 'Courier New', Courier, monospace;
        color: #334155;
        margin-top: 8px;
        overflow-x: auto;
        font-size: 0.95rem;
    }

    /* Print Stylesheet for Clean Academic/Thesis PDF generation */
    @media print {
        /* Hide all interactive/color web elements */
        #sidebar, .d-print-none, .accordion-button::after {
            display: none !important;
        }
        
        /* Reset spacing and background */
        .wrapper, .main-container, #content, .container-fluid {
            display: block !important;
            width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
            background: #ffffff !important;
            box-shadow: none !important;
        }

        /* Use standard thesis/academic serif typography */
        body, p, span, div, table, tr, th, td, h1, h2, h3, h4, h5, h6, code, pre, button, a {
            font-family: "Times New Roman", Times, serif !important;
            font-size: 12pt !important;
            color: #000000 !important;
            line-height: 1.5 !important;
        }

        h5, .accordion-header {
            page-break-after: avoid;
        }

        /* Format accordion into simple text document sections */
        .accordion {
            background: transparent !important;
        }
        .accordion-item {
            border: none !important;
            margin-bottom: 25px !important;
            background: transparent !important;
            box-shadow: none !important;
            page-break-inside: auto !important;
        }
        .accordion-collapse {
            display: block !important;
            height: auto !important;
            visibility: visible !important;
        }
        .accordion-button {
            display: block !important;
            width: 100% !important;
            background: transparent !important;
            color: #000000 !important;
            font-size: 12pt;
            font-weight: bold;
            border: none !important;
            border-bottom: 1.5px solid #000000 !important;
            padding: 8px 0 !important;
            margin-top: 25px !important;
            margin-bottom: 12px !important;
            pointer-events: none !important;
            box-shadow: none !important;
            text-align: left !important;
        }
        .accordion-button .step-badge {
            display: none !important; /* Hide step circle badge */
        }

        /* Main executive card formatting */
        .premium-card, .card {
            border: none !important;
            box-shadow: none !important;
            background: transparent !important;
            margin-bottom: 25px !important;
        }
        .card-header {
            background: transparent !important;
            color: #000000 !important;
            border: none !important;
            border-bottom: 1.5px solid #000000 !important;
            padding: 8px 0 !important;
            font-size: 12pt !important;
            font-weight: bold !important;
        }

        /* Clean academic thesis tables (thin horizontal borders) */
        .table-responsive {
            display: block !important;
            overflow: visible !important;
            width: 100% !important;
        }
        .premium-table, table {
            width: 100% !important;
            border-collapse: collapse !important;
            margin-top: 10px !important;
            margin-bottom: 20px !important;
            page-break-inside: auto !important;
        }
        tr {
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }
        .premium-table th, table th {
            background: transparent !important;
            color: #000000 !important;
            border-top: 1.5px solid #000000 !important;
            border-bottom: 1.5px solid #000000 !important;
            font-weight: bold !important;
            padding: 6px !important;
            font-size: 12pt !important;
        }
        .premium-table td, table td {
            background: transparent !important;
            color: #000000 !important;
            border-bottom: 1px solid #e0e0e0 !important;
            padding: 6px !important;
            font-size: 12pt !important;
        }

        /* Simplify badges to normal text */
        .badge {
            background: transparent !important;
            color: #000000 !important;
            border: none !important;
            padding: 0 !important;
            font-size: 12pt !important;
            font-weight: normal !important;
            box-shadow: none !important;
        }
        .rank-gold, .rank-silver, .rank-bronze, .rank-normal {
            background: transparent !important;
            color: #000000 !important;
            border: none !important;
            font-weight: bold !important;
        }

        /* Simplify matrix style in print */
        .matrix-wrapper {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            margin: 10px 0 !important;
        }
        .matrix-label {
            color: #000000 !important;
            font-size: 12pt !important;
            font-weight: bold !important;
        }
        .matrix-bracket-left, .matrix-bracket-right {
            border-color: #000000 !important;
            border-width: 2px !important;
        }
        .matrix-grid {
            color: #000000 !important;
            font-size: 11pt !important;
        }
        .math-formula-block {
            background: transparent !important;
            border: 1px dashed #000000 !important;
            color: #000000 !important;
            padding: 8px !important;
            font-size: 10pt !important;
        }

        /* Hide icons in print */
        i.bi, .bi {
            display: none !important;
        }

        /* Clean explanation text block */
        .bg-light {
            background: transparent !important;
            border: none !important;
            padding: 0 !important;
        }
        .border-top {
            border-top: none !important;
        }
        .border-start {
            border-left: none !important;
        }
        .shadow-xs, .shadow-sm {
            box-shadow: none !important;
        }
        .text-success, .text-danger {
            color: #000000 !important;
            font-weight: bold !important;
        }
    }
</style>

<div class="container-fluid p-4">
    <!-- PRINT ONLY HEADER -->
    <div class="d-none d-print-block text-center mb-4">
        <h4 class="fw-bold text-uppercase" style="font-family: 'Times New Roman', Times, serif; font-size: 16pt; margin-bottom: 2px;">Laporan Hasil Perhitungan SPK Metode MOORA</h4>
        <p class="small text-muted" style="font-family: 'Times New Roman', Times, serif; font-size: 10pt; margin-bottom: 8px;">Sistem Pendukung Keputusan Pemilihan Rute Pendakian Gunung</p>
        <hr style="border-top: 2px double #000; margin: 0 auto; opacity: 1; width: 100%;">
    </div>

    <!-- Header Banner -->
    <div class="p-4 mb-4 hasil-header shadow-sm d-flex justify-content-between align-items-center flex-wrap gap-3 d-print-none" style="border-radius: 16px;">
        <div>
            <h3 class="fw-bold m-0"><i class="bi bi-calculator me-2"></i>Hasil Perhitungan SPK (Metode MOORA)</h3>
            <p class="opacity-75 small m-0 mt-1">Pemeringkatan rute pendakian dan armada bus berdasarkan optimasi kriteria keuntungan (Benefit) dan biaya (Cost).</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <button onclick="window.print()" class="btn btn-light text-dark fw-bold shadow-sm d-print-none px-3 py-2" style="border-radius: 10px;">
                <i class="bi bi-printer-fill me-2 text-primary"></i>Cetak Laporan PDF
            </button>
            <div class="bg-white bg-opacity-10 p-2.5 rounded-3 border border-white border-opacity-10 text-end d-none d-lg-block">
                <span class="text-white fw-bold d-block small">Rumus Optimasi MOORA:</span>
                <code class="text-warning fw-bold fs-6">Yi = (Σ Max Benefit) - (Σ Min Cost)</code>
            </div>
        </div>
    </div>

    <!-- FILTER ANALYSIS CARD (d-print-none) -->
    <div class="card border-0 shadow-sm mb-4 d-print-none" style="border-radius: 12px;">
        <div class="card-body p-4">
            <form action="{{ route('admin.hasil.index') }}" method="GET" class="row align-items-center g-3">
                <div class="col-12 col-md-8">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-funnel-fill text-primary fs-4"></i>
                        <div>
                            <h6 class="fw-bold text-dark m-0">Filter Analisis Rute</h6>
                            <small class="text-muted">Pilih Gunung untuk memfilter alternatif rute dan armada yang dihitung dalam perangkingan MOORA.</small>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="input-group">
                        <select name="gunung_id" class="form-select border-secondary-subtle" style="border-radius: 8px 0 0 8px; font-weight: 600;">
                            <option value="">-- Semua Gunung --</option>
                            @foreach($gunungs as $g)
                                <option value="{{ $g->id }}" {{ request('gunung_id') == $g->id ? 'selected' : '' }}>
                                    Gn. {{ $g->nama_gunung }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary fw-bold px-4" style="border-radius: 0 8px 8px 0;">
                            <i class="bi bi-play-fill me-1"></i>Analisis
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- SUMMARY WIDGET CARDS (d-print-none) -->
    <div class="row g-3 mb-4 d-print-none">
        <!-- Card 1: Total Alternatif -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #3b82f6 !important;">
                <div class="card-body d-flex align-items-center justify-content-between py-3">
                    <div>
                        <span class="text-muted text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Total Alternatif</span>
                        <h4 class="fw-bold text-dark m-0 mt-1">{{ $totalAlternatifs }}</h4>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-2.5 rounded-3 text-primary">
                        <i class="bi bi-grid-3x3-gap-fill fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Total Kriteria -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #10b981 !important;">
                <div class="card-body d-flex align-items-center justify-content-between py-3">
                    <div>
                        <span class="text-muted text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Total Kriteria</span>
                        <h4 class="fw-bold text-dark m-0 mt-1">{{ $totalKriterias }}</h4>
                    </div>
                    <div class="bg-success bg-opacity-10 p-2.5 rounded-3 text-success">
                        <i class="bi bi-list-check fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Rekomendasi Teratas -->
        <div class="col-12 col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #f59e0b !important;">
                <div class="card-body d-flex align-items-center justify-content-between py-3">
                    <div style="max-width: 80%;">
                        <span class="text-muted text-uppercase fw-bold d-block" style="font-size: 0.72rem; letter-spacing: 0.5px;">Rekomendasi Rute</span>
                        <span class="fw-bold text-dark text-truncate d-block m-0 mt-1" style="font-size: 0.9rem;" title="{{ $topAlternativeName }}">{{ $topAlternativeName }}</span>
                        @if(!empty($hasil))
                            <small class="text-muted text-truncate d-block" style="font-size: 0.7rem;">{{ $topAlternativeSub }}</small>
                        @endif
                    </div>
                    <div class="bg-warning bg-opacity-10 p-2.5 rounded-3 text-warning">
                        <i class="bi bi-trophy-fill fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Nilai Tertinggi -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #ef4444 !important;">
                <div class="card-body d-flex align-items-center justify-content-between py-3">
                    <div>
                        <span class="text-muted text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Nilai Tertinggi (Yi)</span>
                        <h4 class="fw-bold text-success m-0 mt-1">{{ $topAlternativeSkor }}</h4>
                    </div>
                    <div class="bg-danger bg-opacity-10 p-2.5 rounded-3 text-danger">
                        <i class="bi bi-graph-up-arrow fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CARD: RANKING RESULTS (Always Visible & First) -->
    <div class="card premium-card shadow-sm mb-4">
        <div class="card-header bg-dark text-white py-3 border-0 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold d-flex align-items-center">
                <i class="bi bi-trophy-fill text-warning me-2 d-print-none" style="font-size: 1.25rem;"></i>
                Hasil Akhir dan Rekomendasi Peringkat Rute
            </h6>
            <span class="badge bg-success px-3 py-1.5 d-print-none" style="border-radius: 8px;">Final MOORA preferred</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle premium-table">
                    <thead class="bg-slate text-white" style="background-color: #0f172a;">
                        <tr class="text-center">
                            <th class="py-3 ps-4" style="width: 15%;">Peringkat</th>
                            <th class="py-3" style="width: 10%;">Alternatif</th>
                            <th class="py-3 text-start" style="width: 35%;">Alternatif Rute dan Armada</th>
                            <th class="py-3" style="width: 13%;">Total Benefit (Σ Max)</th>
                            <th class="py-3" style="width: 13%;">Total Cost (Σ Min)</th>
                            <th class="py-3" style="width: 14%;">Skor Akhir (Yi)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hasil as $index => $data)
                        @php
                            // Set custom classes for top 3 ranks
                            if($index == 0) {
                                $rankClass = 'rank-gold';
                                $rankText = '<span class="d-print-none">🏆 </span>Peringkat 1';
                            } elseif($index == 1) {
                                $rankClass = 'rank-silver';
                                $rankText = '<span class="d-print-none">🥈 </span>Peringkat 2';
                            } elseif($index == 2) {
                                $rankClass = 'rank-bronze';
                                $rankText = '<span class="d-print-none">🥉 </span>Peringkat 3';
                            } else {
                                $rankClass = 'rank-normal';
                                $rankText = 'Peringkat ' . ($index + 1);
                            }
                        @endphp
                        <tr class="text-center">
                            <td class="ps-4">
                                <span class="badge {{ $rankClass }} px-3 py-2 fw-bold shadow-xs w-100" style="border-radius: 8px; {{ $index > 2 ? 'font-size: 0.85rem;' : '' }}">
                                    {!! $rankText !!}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-secondary text-white fw-bold px-2 py-1.5" style="border-radius: 6px;">
                                    {{ $altLabels[$data['alt_key']] ?? '-' }}
                                </span>
                            </td>
                            <td class="text-start py-3">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark fs-6">
                                        <i class="bi bi-mountains me-2 text-success d-print-none"></i>Gn. {{ $data['nama_gunung'] }}
                                    </span>
                                    <span class="text-muted small ms-md-4">Jalur: {{ $data['nama_jalur'] }}</span>
                                    <span class="text-secondary small ms-md-4 mt-1">
                                        <i class="bi bi-bus-front me-1 text-primary d-print-none"></i>{{ $data['nama_armada'] }} 
                                        <span class="text-muted" style="font-size: 0.75rem;">({{ $data['start_terminal'] }} &rarr; {{ $data['end_terminal'] }})</span>
                                    </span>
                                </div>
                            </td>
                            <td class="text-secondary fw-semibold">{{ number_format($data['max'], 5, ',', '.') }}</td>
                            <td class="text-secondary fw-semibold">{{ number_format($data['min'], 5, ',', '.') }}</td>
                            <td>
                                <span class="fw-bold fs-5 {{ $data['skor'] >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($data['skor'], 5, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-clipboard-x d-block mb-2" style="font-size: 2.5rem;"></i>
                                Belum ada data penilaian alternatif untuk dihitung. Silakan lengkapi data pada menu Penilaian.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="p-4 bg-light border-top d-print-none">
                <div class="p-3 bg-white rounded-3 border-start border-4 border-primary shadow-xs">
                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-question-circle-fill text-primary me-2"></i>Bagaimana Cara Membaca Hasil Perhitungan Ini?</h6>
                    <p class="mb-0 small text-muted">
                        *   <strong>Total Benefit (Σ Max)</strong>: Akumulasi nilai keuntungan (seperti <em>ketinggian gunung</em> dan <em>tingkat kesulitan</em>). Semakin tinggi nilai tersebut, semakin baik alternatif yang bersangkutan.
                        <br>
                        *   <strong>Total Cost (Σ Min)</strong>: Akumulasi nilai pengeluaran atau beban (seperti <em>biaya simaksi, tarif bus, estimasi waktu perjalanan, dan durasi pendakian</em>). Semakin rendah nilai tersebut, semakin baik alternatif yang bersangkutan.
                        <br>
                        *   <strong>Skor Akhir (Yi)</strong>: Selisih antara Benefit dan Cost <code>(Benefit - Cost)</code>. Alternatif dengan <strong>Skor Akhir tertinggi (Peringkat 1)</strong> merupakan pilihan alternatif pendakian yang paling optimal.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- EXPLANATORY COLLAPSIBLE STEPS (Math Steps, Collapsed by default) -->
    <div class="mb-4">
        <h5 class="fw-bold text-dark mb-3 d-print-none"><i class="bi bi-chevron-expand me-2 text-primary"></i>Tahapan Rinci Perhitungan MOORA (Standard Akademik / Skripsi)</h5>
        
        <div class="accordion" id="accordionCalculation">
            
            <!-- STEP 1: DECISION MATRIX -->
            <div class="accordion-item border-0 mb-3 rounded shadow-xs overflow-hidden">
                <h2 class="accordion-header">
                    <button class="accordion-button accordion-button-custom collapsed p-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseStepOne">
                        <span class="step-badge">1</span>
                        <span>Matriks Keputusan (Nilai Parameter Awal - Matriks X)</span>
                    </button>
                </h2>
                <div id="collapseStepOne" class="accordion-collapse collapse" data-parent="#accordionCalculation">
                    <div class="accordion-body p-0">
                        <div class="p-3 bg-light border-bottom d-print-none">
                            <p class="mb-0 small text-muted">
                                <strong>Penjelasan</strong>: Matriks keputusan merupakan tabel nilai mentah dari parameter alternatif yang telah dimasukkan pada halaman Penilaian. Setiap alternatif dinilai berdasarkan kriteria C1 sampai dengan C6 dengan rentang skor 1 sampai dengan 5.
                            </p>
                        </div>
                        
                        <!-- Formula Info Box (Detail) -->
                        <div class="px-4 pt-4 d-print-none">
                            <div class="alert alert-info border-0 shadow-xs mb-0 d-flex align-items-center gap-2.5" style="border-radius: 8px; background-color: #f0fdf4; border-left: 4px solid #10b981 !important; color: #065f46;">
                                <i class="bi bi-info-circle-fill fs-5 text-success"></i>
                                <span><strong>Rumus kuadrat per kriteria:</strong> &Sigma;(x<sub>ij</sub>)&sup2; = x<sub>1j</sub>&sup2; + x<sub>2j</sub>&sup2; + ... + x<sub>mj</sub>&sup2;</span>
                            </div>
                        </div>



                        <div class="table-responsive">
                            <table class="table table-hover align-middle premium-table mb-0">
                                <thead class="bg-dark text-white">
                                    <tr class="text-center">
                                        <th class="py-3" style="width: 10%;">No</th>
                                        <th class="py-3" style="width: 15%;">Alternatif</th>
                                        <th class="py-3 text-start" style="width: 35%;">Detail Alternatif</th>
                                        @foreach($kriterias as $k)
                                            <th class="py-3">{{ $k->kode_kriteria }}<br><small class="text-white-50 d-print-none" style="font-size: 0.7rem;">({{ ucfirst($k->tipe) }})</small></th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach($alternatifs as $altKey => $alt)
                                    <tr class="text-center">
                                        <td>{{ $no++ }}</td>
                                        <td><span class="badge bg-secondary text-white fw-bold px-2 py-1.5" style="border-radius: 6px;">{{ $altLabels[$altKey] }}</span></td>
                                        <td class="text-start">
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-dark fs-7">Gn. {{ $alt['nama_gunung'] }} <small class="text-muted">({{ $alt['nama_jalur'] }})</small></span>
                                                <small class="text-muted">{{ $alt['nama_armada'] }}</small>
                                            </div>
                                        </td>
                                        @foreach($kriterias as $k)
                                            <td><span class="badge bg-light text-dark border px-2 py-1.5" style="border-radius: 6px;">{{ $alt['items']->where('kriteria_id', $k->id)->first()->nilai ?? 0 }}</span></td>
                                        @endforeach
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="text-center table-secondary fw-bold">
                                        <td colspan="3" class="text-end py-3"><strong>&Sigma;(x<sub>ij</sub>)&sup2;</strong></td>
                                        @foreach($kriterias as $k)
                                            @php
                                                $sumSquares = 0;
                                                foreach($alternatifs as $altKey => $alt) {
                                                    $val = $alt['items']->where('kriteria_id', $k->id)->first()->nilai ?? 0;
                                                    $sumSquares += pow($val, 2);
                                                }
                                            @endphp
                                            <td class="text-dark fw-bold">{{ number_format($sumSquares, 5, ',', '.') }}</td>
                                        @endforeach
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 2: DENOMINATORS -->
            <div class="accordion-item border-0 mb-3 rounded shadow-xs overflow-hidden">
                <h2 class="accordion-header">
                    <button class="accordion-button accordion-button-custom collapsed p-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseStepTwo">
                        <span class="step-badge">2</span>
                        <span>Melakukan Normalisasi Matriks (Penyebut Normalisasi)</span>
                    </button>
                </h2>
                <div id="collapseStepTwo" class="accordion-collapse collapse" data-parent="#accordionCalculation">
                    <div class="accordion-body p-0">
                        <div class="p-3 bg-light border-bottom d-print-none">
                            <p class="mb-0 small text-muted">
                                <strong>Penjelasan</strong>: Oleh karena setiap kriteria memiliki satuan yang berbeda, data tersebut perlu dinormalisasi ke skala desimal 0 sampai dengan 1 agar dapat dibandingkan secara objektif. 
                                Pembagi kriteria dihitung menggunakan rumus akar dari jumlah kuadrat seluruh nilai kriteria pada Matriks Keputusan.
                            </p>
                        </div>

                        <!-- Formula Info Box (Detail) -->
                        <div class="px-4 pt-4 d-print-none">
                            <div class="alert alert-info border-0 shadow-xs mb-0 d-flex align-items-center gap-2.5" style="border-radius: 8px; background-color: #e0f2fe; border-left: 4px solid #0284c7 !important; color: #0369a1;">
                                <i class="bi bi-info-circle-fill fs-5 text-info"></i>
                                <span><strong>Rumus akar untuk normalisasi:</strong> &radic;[ &Sigma;(x<sub>ij</sub>)&sup2; ] = &radic;[ x<sub>1j</sub>&sup2; + x<sub>2j</sub>&sup2; + ... + x<sub>mj</sub>&sup2; ]</span>
                            </div>
                        </div>

                        <div class="p-4 bg-white">
                            <!-- Table of Denominators for screen view (Detail) -->
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle text-center premium-table mb-0">
                                    <thead class="bg-dark text-white">
                                        <tr>
                                            <th class="py-3 text-start" style="width: 25%;">Detail Kriteria & Perhitungan</th>
                                            @foreach($kriterias as $k)
                                                <th class="py-3">{{ $k->kode_kriteria }}<br><small class="text-white-50" style="font-size: 0.75rem;">({{ $k->nama_kriteria }})</small></th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold text-start ps-3 py-3">Jumlah Kuadrat [&Sigma;(x<sub>ij</sub>)&sup2;]</td>
                                            @foreach($kriterias as $k)
                                                @php
                                                    $sumSquares = 0;
                                                    foreach($alternatifs as $altKey => $alt) {
                                                        $val = $alt['items']->where('kriteria_id', $k->id)->first()->nilai ?? 0;
                                                        $sumSquares += pow($val, 2);
                                                    }
                                                @endphp
                                                <td class="text-secondary fw-semibold">{{ number_format($sumSquares, 5, ',', '.') }}</td>
                                            @endforeach
                                        </tr>
                                        <tr class="table-light">
                                            <td class="fw-bold text-start ps-3 py-3">Akar Kuadrat (Penyebut) [&radic;(&Sigma;(x<sub>ij</sub>)&sup2;)]</td>
                                            @foreach($kriterias as $k)
                                                <td class="text-primary fw-bold">{{ number_format($pembagi[$k->id] ?? 1, 5, ',', '.') }}</td>
                                            @endforeach
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Detail of formula breakdown per criterion (Detail) -->
                            <div class="mt-4">
                                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-gear-fill text-muted me-1 d-print-none"></i>Detail Penjabaran Rumus per Kriteria:</h6>
                                <div class="row g-3">
                                    @foreach($kriterias as $k)
                                    @php
                                        $squareTerms = [];
                                        $sumSquares = 0;
                                        foreach($alternatifs as $altKey => $alt) {
                                            $val = $alt['items']->where('kriteria_id', $k->id)->first()->nilai ?? 0;
                                            $squareTerms[] = "{$val}²";
                                            $sumSquares += pow($val, 2);
                                        }
                                        $formulaStr = implode(' + ', $squareTerms);
                                        $denom = $pembagi[$k->id] ?? 1;
                                    @endphp
                                    <div class="col-12 col-lg-6">
                                        <div class="p-3 bg-light rounded-3 border">
                                            <span class="fw-bold text-dark fs-6 d-block mb-1">{{ $k->kode_kriteria }} ({{ $k->nama_kriteria }}):</span>
                                            <code class="text-secondary fs-7 font-monospace">
                                                &radic;[ {!! $formulaStr !!} ] = &radic;[ {{ $sumSquares }} ] = <strong>{{ number_format($denom, 5, ',', '.') }}</strong>
                                            </code>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 3: DECISION MATRIX NORMALIZATION (Tabel 3.13) -->
            <div class="accordion-item border-0 mb-3 rounded shadow-xs overflow-hidden">
                <h2 class="accordion-header">
                    <button class="accordion-button accordion-button-custom collapsed p-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseStepThree">
                        <span class="step-badge">3</span>
                        <span>Matriks Normalisasi Keputusan (X*ij)</span>
                    </button>
                </h2>
                <div id="collapseStepThree" class="accordion-collapse collapse" data-parent="#accordionCalculation">
                    <div class="accordion-body p-0">
                        <div class="p-3 bg-light border-bottom d-print-none">
                            <p class="mb-0 small text-muted">
                                <strong>Penjelasan</strong>: Hasil pembagian setiap nilai pada matriks keputusan (Langkah 1) dengan nilai penyebut normalisasi kriteria yang bersangkutan (Langkah 2). Nilai desimal dibulatkan menjadi 5 angka di belakang koma.
                            </p>
                        </div>

                        <!-- Formula Info Box (Detail) -->
                        <div class="px-4 pt-4 d-print-none">
                            <div class="alert alert-info border-0 shadow-xs mb-0 d-flex align-items-center gap-2.5" style="border-radius: 8px; background-color: #eff6ff; border-left: 4px solid #3b82f6 !important; color: #1e3a8a;">
                                <i class="bi bi-info-circle-fill fs-5 text-primary"></i>
                                <span><strong>Rumus normalisasi:</strong> X*<sub>ij</sub> = x<sub>ij</sub> / &radic;[ &Sigma;<sub>i=1</sub><sup>m</sup> x<sub>ij</sub>&sup2; ]</span>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle premium-table mb-0">
                                <thead class="bg-dark text-white">
                                    <tr class="text-center">
                                        <th class="py-3" style="width: 10%;">No</th>
                                        <th class="py-3" style="width: 15%;">Alternatif</th>
                                        <th class="py-3 text-start" style="width: 35%;">Detail Alternatif</th>
                                        @foreach($kriterias as $k)
                                            <th class="py-3">{{ $k->kode_kriteria }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach($alternatifs as $altKey => $alt)
                                    <tr class="text-center">
                                        <td>{{ $no++ }}</td>
                                        <td><span class="badge bg-secondary text-white fw-bold px-2 py-1.5" style="border-radius: 6px;">{{ $altLabels[$altKey] }}</span></td>
                                        <td class="text-start">
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-dark fs-7">Gn. {{ $alt['nama_gunung'] }} <small class="text-muted">({{ $alt['nama_jalur'] }})</small></span>
                                                <small class="text-muted">{{ $alt['nama_armada'] }}</small>
                                            </div>
                                        </td>
                                        @foreach($kriterias as $k)
                                            <td class="text-secondary fw-semibold">
                                                {{ number_format($normalisasi[$altKey]['nilai'][$k->id] ?? 0, 5, ',', '.') }}
                                            </td>
                                        @endforeach
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 4: WEIGHTED NORMALIZATION MATRIX (Tabel 3.14) -->
            <div class="accordion-item border-0 mb-3 rounded shadow-xs overflow-hidden">
                <h2 class="accordion-header">
                    <button class="accordion-button accordion-button-custom collapsed p-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseStepFour">
                        <span class="step-badge">4</span>
                        <span>Matriks Normalisasi Terbobot (Yij)</span>
                    </button>
                </h2>
                <div id="collapseStepFour" class="accordion-collapse collapse" data-parent="#accordionCalculation">
                    <div class="accordion-body p-0">
                        <div class="p-3 bg-light border-bottom d-print-none">
                            <p class="mb-0 small text-muted">
                                <strong>Penjelasan</strong>: Hasil perkalian antara nilai matriks keputusan ternormalisasi (Langkah 3) dengan bobot kriteria masing-masing. Bobot kriteria tertera di bagian atas kolom.
                            </p>
                        </div>

                        <!-- Formula Info Box (Detail) -->
                        <div class="px-4 pt-4 d-print-none">
                            <div class="alert alert-info border-0 shadow-xs mb-0 d-flex align-items-center gap-2.5" style="border-radius: 8px; background-color: #fffbeb; border-left: 4px solid #d97706 !important; color: #78350f;">
                                <i class="bi bi-info-circle-fill fs-5 text-warning"></i>
                                <span><strong>Rumus nilai terbobot:</strong> y<sub>ij</sub> = X*<sub>ij</sub> &times; w<sub>j</sub> <br><small class="opacity-75">(X*<sub>ij</sub>: nilai normalisasi, w<sub>j</sub>: bobot kriteria)</small></span>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle premium-table mb-0">
                                <thead class="bg-dark text-white">
                                    <tr class="text-center">
                                        <th class="py-3" style="width: 10%;">No</th>
                                        <th class="py-3" style="width: 15%;">Alternatif</th>
                                        <th class="py-3 text-start" style="width: 35%;">Detail Alternatif</th>
                                        @foreach($kriterias as $k)
                                            <th class="py-3">
                                                {{ $k->kode_kriteria }}<br>
                                                <small class="text-white-50" style="font-size: 0.75rem;">({{ number_format($k->bobot, 2, ',', '.') }})</small>
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach($alternatifs as $altKey => $alt)
                                    <tr class="text-center">
                                        <td>{{ $no++ }}</td>
                                        <td><span class="badge bg-secondary text-white fw-bold px-2 py-1.5" style="border-radius: 6px;">{{ $altLabels[$altKey] }}</span></td>
                                        <td class="text-start">
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-dark fs-7">Gn. {{ $alt['nama_gunung'] }} <small class="text-muted">({{ $alt['nama_jalur'] }})</small></span>
                                                <small class="text-muted">{{ $alt['nama_armada'] }}</small>
                                            </div>
                                        </td>
                                        @foreach($kriterias as $k)
                                            <td class="text-secondary fw-semibold">
                                                {{ number_format($terbobot[$altKey]['nilai'][$k->id] ?? 0, 5, ',', '.') }}
                                            </td>
                                        @endforeach
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 5: OPTIMIZATION VALUES (Tabel 3.15) -->
            <div class="accordion-item border-0 mb-3 rounded shadow-xs overflow-hidden">
                <h2 class="accordion-header">
                    <button class="accordion-button accordion-button-custom collapsed p-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseStepFive">
                        <span class="step-badge">5</span>
                        <span>Perhitungan Nilai Optimasi (Yi) dan Perangkingan</span>
                    </button>
                </h2>
                <div id="collapseStepFive" class="accordion-collapse collapse" data-parent="#accordionCalculation">
                    <div class="accordion-body p-0">
                        <div class="p-3 bg-light border-bottom d-print-none">
                            <p class="mb-0 small text-muted">
                                <strong>Penjelasan</strong>: Nilai optimasi (Yi) diperoleh dengan menjumlahkan nilai terbobot kriteria keuntungan (Benefit) lalu dikurangi dengan jumlah nilai terbobot kriteria biaya (Cost). Pada tabel ini, alternatif diurutkan berdasarkan kodenya (A1 s.d An).
                            </p>
                        </div>

                        <!-- Formula Info Box (Detail) -->
                        <div class="px-4 pt-4 d-print-none">
                            <div class="alert alert-info border-0 shadow-xs mb-0 d-flex align-items-center gap-2.5" style="border-radius: 8px; background-color: #fef2f2; border-left: 4px solid #ef4444 !important; color: #7f1d1d;">
                                <i class="bi bi-info-circle-fill fs-5 text-danger"></i>
                                <span><strong>Rumus nilai akhir MOORA:</strong> Y<sub>i</sub> = &Sigma; (w<sub>j</sub> &times; X*<sub>ij</sub>) (benefit) - &Sigma; (w<sub>j</sub> &times; X*<sub>ij</sub>) (cost)</span>
                            </div>
                        </div>
                        <div class="p-3 bg-white border-bottom">
                            <h6 class="fw-bold text-dark mb-1">Rumus Optimasi Yi:</h6>
                            <div class="math-formula-block text-center fs-6 py-3">
                                Y<sub>i</sub> = &Sigma;<sub>j&isin;Benefit</sub> Y<sub>ij</sub> - &Sigma;<sub>j&isin;Cost</sub> Y<sub>ij</sub>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle premium-table mb-0">
                                <thead class="bg-dark text-white">
                                    <tr class="text-center">
                                        <th class="py-3" style="width: 10%;">No</th>
                                        <th class="py-3" style="width: 15%;">Alternatif</th>
                                        <th class="py-3" style="width: 25%;">Benefit (Bi)<br><small class="text-white-50" style="font-size: 0.75rem;">({{ $benefitCodes }})</small></th>
                                        <th class="py-3" style="width: 25%;">Cost (Ci)<br><small class="text-white-50" style="font-size: 0.75rem;">({{ $costCodes }})</small></th>
                                        <th class="py-3" style="width: 15%;">Yi = Bi - Ci</th>
                                        <th class="py-3" style="width: 10%;">Peringkat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach($alternatifs as $altKey => $alt)
                                    @php
                                        $maxVal = 0;
                                        $minVal = 0;
                                        foreach($kriterias as $k) {
                                            $val = $terbobot[$altKey]['nilai'][$k->id] ?? 0;
                                            if(strtolower($k->tipe) == 'benefit') {
                                                $maxVal += $val;
                                            } else {
                                                $minVal += $val;
                                            }
                                        }
                                        $yi = $maxVal - $minVal;
                                        
                                        // Cari peringkat berdasarkan sorted $hasil
                                        $rank = 1;
                                        foreach($hasil as $hIdx => $hData) {
                                            if($hData['alt_key'] == $altKey) {
                                                $rank = $hIdx + 1;
                                                break;
                                            }
                                        }
                                    @endphp
                                    <tr class="text-center">
                                        <td>{{ $no++ }}</td>
                                        <td><span class="badge bg-secondary text-white fw-bold px-2 py-1.5" style="border-radius: 6px;">{{ $altLabels[$altKey] }}</span></td>
                                        <td class="text-secondary fw-semibold">{{ number_format($maxVal, 5, ',', '.') }}</td>
                                        <td class="text-secondary fw-semibold">{{ number_format($minVal, 5, ',', '.') }}</td>
                                        <td>
                                            <span class="fw-bold {{ $yi >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ number_format($yi, 5, ',', '.') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $rank <= 3 ? 'bg-warning text-dark' : 'bg-light text-dark border' }} fw-bold">
                                                {{ $rank }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 6: FINAL CONCLUSION RANKING (Tabel 3.16) -->
            <div class="accordion-item border-0 mb-3 rounded shadow-xs overflow-hidden">
                <h2 class="accordion-header">
                    <button class="accordion-button accordion-button-custom collapsed p-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseStepSix">
                        <span class="step-badge">6</span>
                        <span>Kesimpulan Perangkingan</span>
                    </button>
                </h2>
                <div id="collapseStepSix" class="accordion-collapse collapse" data-parent="#accordionCalculation">
                    <div class="accordion-body p-0">
                        <div class="p-3 bg-light border-bottom d-print-none">
                            <p class="mb-0 small text-muted">
                                <strong>Penjelasan</strong>: Berdasarkan hasil perhitungan nilai optimasi Yi pada langkah sebelumnya (Perhitungan Nilai Optimasi), urutan alternatif dari Peringkat 1 hingga peringkat terakhir adalah sebagai berikut.
                            </p>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle premium-table mb-0">
                                <thead class="bg-dark text-white">
                                    <tr class="text-center">
                                        <th class="py-3" style="width: 15%;">Peringkat</th>
                                        <th class="py-3" style="width: 15%;">Alternatif</th>
                                        <th class="py-3 text-start" style="width: 50%;">Nama Rute & Armada</th>
                                        <th class="py-3" style="width: 20%;">Nilai Yi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($hasil as $index => $data)
                                    @php
                                        if($index == 0) {
                                            $badgeClass = 'rank-gold';
                                            $rankText = '<span class="d-print-none">🏆 </span>Peringkat 1';
                                        } elseif($index == 1) {
                                            $badgeClass = 'rank-silver';
                                            $rankText = '<span class="d-print-none">🥈 </span>Peringkat 2';
                                        } elseif($index == 2) {
                                            $badgeClass = 'rank-bronze';
                                            $rankText = '<span class="d-print-none">🥉 </span>Peringkat 3';
                                        } else {
                                            $badgeClass = 'rank-normal';
                                            $rankText = 'Peringkat ' . ($index + 1);
                                        }
                                    @endphp
                                    <tr class="text-center">
                                        <td>
                                            <span class="badge {{ $badgeClass }} px-3 py-2 fw-bold shadow-xs w-100" style="border-radius: 8px;">
                                                {!! $rankText !!}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary text-white fw-bold px-2 py-1.5" style="border-radius: 6px;">
                                                {{ $altLabels[$data['alt_key']] ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="text-start">
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-dark fs-6">Gn. {{ $data['nama_gunung'] }}</span>
                                                <span class="text-muted small">Jalur: {{ $data['nama_jalur'] }}</span>
                                                <span class="text-secondary small mt-1">
                                                    <i class="bi bi-bus-front me-1 text-primary d-print-none"></i>{{ $data['nama_armada'] }}
                                                    <span class="text-muted" style="font-size: 0.75rem;">({{ $data['start_terminal'] }} &rarr; {{ $data['end_terminal'] }})</span>
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-bold fs-5 {{ $data['skor'] >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ number_format($data['skor'], 5, ',', '.') }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection