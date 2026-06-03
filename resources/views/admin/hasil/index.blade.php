@extends('layouts.admin')

@section('content')
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

    /* Print Stylesheet for Premium PDF generation */
    @media print {
        #sidebar, .d-print-none {
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
        .table-responsive {
            display: block !important;
            overflow: visible !important;
            width: 100% !important;
        }
        tr {
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }
        .collapse {
            display: block !important;
            height: auto !important;
            visibility: visible !important;
        }
        .accordion-button::after {
            display: none !important;
        }
        .accordion-button {
            background-color: #f8fafc !important;
            color: #3b82f6 !important;
            pointer-events: none !important;
        }
        .accordion-item {
            border: 1px solid #cbd5e1 !important;
            margin-bottom: 20px !important;
            page-break-inside: avoid;
        }
        #content {
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
        }
        body {
            background-color: #ffffff !important;
            font-size: 11pt;
        }
        .container-fluid {
            padding: 0 !important;
        }
        .hasil-header {
            background: #0f172a !important;
            color: #ffffff !important;
            border: 1px solid #000000 !important;
            border-radius: 12px !important;
            padding: 20px !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .hasil-header h3, .hasil-header p {
            color: #ffffff !important;
        }
        .premium-card {
            box-shadow: none !important;
            border: 1px solid #cbd5e1 !important;
        }
        .premium-table th {
            background-color: #0f172a !important;
            color: #ffffff !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .premium-table td {
            border-bottom: 1px solid #e2e8f0 !important;
        }
        .rank-gold {
            background-color: #fef08a !important;
            color: #854d0e !important;
            border-color: #eab308 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .rank-silver {
            background-color: #f1f5f9 !important;
            color: #334155 !important;
            border-color: #94a3b8 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .rank-bronze {
            background-color: #ffedd5 !important;
            color: #9a3412 !important;
            border-color: #f97316 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .rank-normal {
            background-color: #f8fafc !important;
            color: #64748b !important;
            border-color: #cbd5e1 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>

<div class="container-fluid p-4">
    <!-- Header Banner -->
    <div class="p-4 mb-4 hasil-header shadow-sm d-flex justify-content-between align-items-center flex-wrap gap-3">
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

    <!-- MAIN CARD: RANKING RESULTS (Always Visible & First) -->
    <div class="card premium-card shadow-sm mb-4">
        <div class="card-header bg-dark text-white py-3 border-0 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold d-flex align-items-center">
                <i class="bi bi-trophy-fill text-warning me-2" style="font-size: 1.25rem;"></i>
                Hasil Akhir dan Rekomendasi Peringkat Rute
            </h6>
            <span class="badge bg-success px-3 py-1.5" style="border-radius: 8px;">Final MOORA preferred</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle premium-table">
                    <thead class="bg-slate text-white" style="background-color: #0f172a;">
                        <tr class="text-center">
                            <th class="py-3 ps-4" style="width: 15%;">Peringkat</th>
                            <th class="py-3 text-start" style="width: 35%;">Alternatif Rute dan Armada</th>
                            <th class="py-3" style="width: 15%;">Total Benefit (Σ Max)</th>
                            <th class="py-3" style="width: 15%;">Total Cost (Σ Min)</th>
                            <th class="py-3" style="width: 15%;">Skor Akhir (Yi)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hasil as $index => $data)
                        @php
                            // Set custom classes for top 3 ranks
                            if($index == 0) {
                                $rankClass = 'rank-gold';
                                $rankText = '🏆 Peringkat 1';
                            } elseif($index == 1) {
                                $rankClass = 'rank-silver';
                                $rankText = '🥈 Peringkat 2';
                            } elseif($index == 2) {
                                $rankClass = 'rank-bronze';
                                $rankText = '🥉 Peringkat 3';
                            } else {
                                $rankClass = 'rank-normal';
                                $rankText = '#' . ($index + 1);
                            }
                        @endphp
                        <tr class="text-center">
                            <td class="ps-4">
                                <span class="badge {{ $rankClass }} px-3 py-2 fw-bold shadow-xs w-100" style="border-radius: 8px; {{ $index > 2 ? 'font-size: 0.85rem;' : '' }}">
                                    {{ $rankText }}
                                </span>
                            </td>
                            <td class="text-start py-3">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark fs-6">
                                        <i class="bi bi-mountains me-2 text-success"></i>Gn. {{ $data['nama_gunung'] }}
                                    </span>
                                    <span class="text-muted small ms-4">Jalur: {{ $data['nama_jalur'] }}</span>
                                    <span class="text-secondary small ms-4 mt-1">
                                        <i class="bi bi-bus-front me-1 text-primary"></i>{{ $data['nama_armada'] }} 
                                        <span class="text-muted" style="font-size: 0.75rem;">({{ $data['start_terminal'] }} &rarr; {{ $data['end_terminal'] }})</span>
                                    </span>
                                </div>
                            </td>
                            <td class="text-secondary fw-semibold">{{ number_format($data['max'], 4) }}</td>
                            <td class="text-secondary fw-semibold">{{ number_format($data['min'], 4) }}</td>
                            <td>
                                <span class="fw-bold fs-5 {{ $data['skor'] >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($data['skor'], 4) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-clipboard-x d-block mb-2" style="font-size: 2.5rem;"></i>
                                Belum ada data penilaian alternatif untuk dihitung. Silakan lengkapi data pada menu Penilaian.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="p-4 bg-light border-top">
                <div class="p-3 bg-white rounded-3 border-start border-4 border-primary shadow-xs">
                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-question-circle-fill text-primary me-2"></i>Bagaimana Cara Membaca Hasil Perhitungan Ini?</h6>
                    <p class="mb-0 small text-muted">
                        *   <strong>Total Benefit (Σ Max)</strong>: Akumulasi nilai keuntungan (seperti <em>ketinggian gunung</em>). Semakin tinggi nilai tersebut, semakin baik alternatif yang bersangkutan.
                        <br>
                        *   <strong>Total Cost (Σ Min)</strong>: Akumulasi nilai pengeluaran atau beban (seperti <em>biaya simaksi, tarif bus, tingkat kesulitan, dan durasi perjalanan</em>). Semakin rendah nilai tersebut, semakin baik alternatif yang bersangkutan.
                        <br>
                        *   <strong>Skor Akhir (Yi)</strong>: Selisih antara Benefit dan Cost <code>(Benefit - Cost)</code>. Alternatif dengan <strong>Skor Akhir tertinggi (Peringkat 1)</strong> merupakan pilihan alternatif pendakian yang paling optimal.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- EXPLANATORY COLLAPSIBLE STEPS (Math Steps, Collapsed by default) -->
    <div class="mb-4">
        <h5 class="fw-bold text-dark mb-3 d-print-none"><i class="bi bi-chevron-expand me-2 text-primary"></i>Tahapan Rinci Perhitungan MOORA</h5>
        
        <div class="accordion" id="accordionCalculation">
            
            <!-- STEP 1: DECISION MATRIX -->
            <div class="accordion-item border-0 mb-3 rounded shadow-xs overflow-hidden">
                <h2 class="accordion-header">
                    <button class="accordion-button accordion-button-custom collapsed p-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseStepOne">
                        <span class="step-badge">1</span>
                        <span>Matriks Keputusan (Nilai Parameter Awal)</span>
                    </button>
                </h2>
                <div id="collapseStepOne" class="accordion-collapse collapse" data-parent="#accordionCalculation">
                    <div class="accordion-body p-0">
                        <div class="p-3 bg-light border-bottom">
                            <p class="mb-0 small text-muted">
                                <strong>Penjelasan</strong>: Matriks keputusan merupakan tabel nilai mentah dari parameter alternatif yang telah dimasukkan pada halaman Penilaian. Setiap alternatif dinilai berdasarkan kriteria C1 sampai dengan C6 dengan rentang skor 1 sampai dengan 5.
                            </p>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle premium-table mb-0">
                                <thead class="bg-dark text-white">
                                    <tr class="text-center">
                                        <th class="py-3 text-start ps-4" style="width: 40%;">Alternatif Rute dan Armada</th>
                                        @foreach($kriterias as $k)
                                            <th class="py-3">{{ $k->kode_kriteria }}<br><small class="text-muted" style="font-size: 0.7rem;">({{ ucfirst($k->tipe) }})</small></th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($matriks as $key => $alt)
                                    <tr class="text-center">
                                        <td class="text-start ps-4">
                                            <span class="fw-bold text-dark d-block">Gn. {{ $alt['nama_gunung'] }} <small class="text-muted fw-normal">({{ $alt['nama_jalur'] }})</small></span>
                                            <small class="text-secondary">{{ $alt['nama_armada'] }}</small>
                                        </td>
                                        @foreach($kriterias as $k)
                                            <td><span class="badge bg-light text-dark border px-2 py-1.5" style="border-radius: 6px;">{{ $alt['nilai'][$k->id] ?? 0 }}</span></td>
                                        @endforeach
                                    </tr>
                                    @endforeach
                                </tbody>
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
                        <span>Nilai Pembagi Kriteria (Untuk Normalisasi)</span>
                    </button>
                </h2>
                <div id="collapseStepTwo" class="accordion-collapse collapse" data-parent="#accordionCalculation">
                    <div class="accordion-body p-4">
                        <p class="small text-muted">
                            <strong>Penjelasan</strong>: Oleh karena setiap kriteria memiliki satuan yang berbeda (contoh: ketinggian dalam mdpl, tarif bus dalam Rupiah, dan tingkat kesulitan dalam teks), data tersebut perlu dinormalisasi ke skala desimal 0 sampai dengan 1 agar dapat dibandingkan secara objektif.
                            <br>
                            <strong>Rumus Pembagi Kriteria</strong>: Akar dari jumlah kuadrat seluruh nilai kriteria pada tabel pertama (Matriks Keputusan).
                        </p>
                        <hr class="opacity-25 my-3">
                        <div class="row g-3">
                            @foreach($kriterias as $k)
                            <div class="col-md-4 col-sm-6">
                                <div class="p-3 bg-light rounded-3 border d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="fw-bold text-dark d-block" style="font-size: 0.85rem;">{{ $k->kode_kriteria }} - {{ $k->nama_kriteria }}</span>
                                        <small class="text-muted">Tipe: {{ $k->tipe }} | Bobot: {{ $k->bobot }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-primary text-white fw-bold py-2 px-2.5 fs-6" style="border-radius: 8px;" title="Nilai Pembagi Normalisasi">
                                            {{ number_format($pembagi[$k->id] ?? 1, 4) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 3: NORMALIZED WEIGHTED MATRIX -->
            <div class="accordion-item border-0 mb-3 rounded shadow-xs overflow-hidden">
                <h2 class="accordion-header">
                    <button class="accordion-button accordion-button-custom collapsed p-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseStepThree">
                        <span class="step-badge">3</span>
                        <span>Matriks Ternormalisasi Terbobot (Hasil Akhir Nilai Kriteria)</span>
                    </button>
                </h2>
                <div id="collapseStepThree" class="accordion-collapse collapse" data-parent="#accordionCalculation">
                    <div class="accordion-body p-0">
                        <div class="p-3 bg-light border-bottom">
                            <p class="mb-0 small text-muted">
                                <strong>Penjelasan</strong>: Nilai parameter awal dari tabel pertama dibagi dengan nilai Pembagi Kriteria (Langkah 2) untuk menghasilkan matriks ternormalisasi. Selanjutnya, hasil pembagian tersebut dikalikan dengan bobot masing-masing kriteria untuk menghasilkan <strong>Nilai Terbobot</strong> sebagai berikut.
                            </p>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle premium-table mb-0">
                                <thead class="bg-dark text-white">
                                    <tr class="text-center">
                                        <th class="py-3 text-start ps-4" style="width: 40%;">Alternatif Rute dan Armada</th>
                                        @foreach($kriterias as $k)
                                            <th class="py-3">{{ $k->kode_kriteria }}<br><small class="text-muted" style="font-size: 0.7rem;">(Bobot: {{ $k->bobot }})</small></th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($terbobot as $key => $alt)
                                    <tr class="text-center">
                                        <td class="text-start ps-4">
                                            <span class="fw-bold text-dark d-block">Gn. {{ $alt['nama_gunung'] }} <small class="text-muted fw-normal">({{ $alt['nama_jalur'] }})</small></span>
                                            <small class="text-secondary">{{ $alt['nama_armada'] }}</small>
                                        </td>
                                        @foreach($kriterias as $k)
                                            <td class="text-secondary fw-semibold">{{ number_format($alt['nilai'][$k->id] ?? 0, 4) }}</td>
                                        @endforeach
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