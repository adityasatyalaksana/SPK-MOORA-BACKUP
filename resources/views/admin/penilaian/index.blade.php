@extends('layouts.admin')

@section('content')
<style>
    /* Styling khusus untuk tabel Penilaian agar rapi dan premium */
    .assessment-card {
        border-radius: 16px;
        overflow: hidden;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        background: #ffffff;
    }
    .assessment-table {
        margin-bottom: 0;
    }
    .assessment-table th {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.75px;
        font-weight: 700;
        vertical-align: middle;
        padding: 16px 12px;
        border-bottom: none;
        background-color: #0f172a;
        color: #ffffff;
    }
    .assessment-table td {
        vertical-align: middle;
        padding: 16px 12px;
        border-color: #f1f5f9;
    }
    .alternative-cell {
        min-width: 280px;
    }
    .kriteria-cell {
        min-width: 130px;
        text-align: center;
    }
    .score-badge {
        font-size: 0.75rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 6px;
        display: inline-block;
        margin-top: 4px;
        transition: all 0.2s ease;
    }
    .score-badge-1 { background-color: #ffebee; color: #c62828; border: 1px solid #ffcdd2; }
    .score-badge-2 { background-color: #fff3e0; color: #e65100; border: 1px solid #ffe0b2; }
    .score-badge-3 { background-color: #fffde7; color: #f57f17; border: 1px solid #fff9c4; }
    .score-badge-4 { background-color: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }
    .score-badge-5 { background-color: #e3f2fd; color: #0d6efd; border: 1px solid #bbdefb; }

    .modal-premium {
        border-radius: 20px;
        overflow: hidden;
        border: none;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    .modal-premium .modal-header {
        border-bottom: none;
        padding: 24px;
    }
    .modal-premium .modal-body {
        padding: 24px;
    }
    .modal-premium .modal-footer {
        border-top: none;
        padding: 20px 24px;
        background-color: #f8fafc;
    }
    .form-select-custom {
        padding: 12px 16px;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        font-weight: 500;
        color: #334155;
        transition: all 0.3s ease;
    }
    .form-select-custom:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
    }

    /* Custom Searchable Dropdown Styles */
    .dropdown-item-card {
        cursor: pointer;
        transition: all 0.2s ease;
        border: 1px solid #e2e8f0;
        background-color: #ffffff;
        text-align: left;
    }
    .dropdown-item-card:hover {
        background-color: #f8fafc;
        border-color: #3b82f6;
    }
    .dropdown-item-card.selected {
        background-color: #eff6ff;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }
    .custom-dropdown-menu {
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
        border: 1px solid #e2e8f0 !important;
        background-color: #ffffff;
    }
    .search-input {
        border-radius: 10px;
        padding: 10px 14px;
        border: 2px solid #e2e8f0;
    }
    .search-input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }
    .dropdown-btn-custom {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 16px;
        background-color: #f8fafc;
        transition: all 0.2s ease;
    }
    .dropdown-btn-custom:hover, .dropdown-btn-custom:focus {
        border-color: #3b82f6;
        background-color: #ffffff;
    }
    .val-helper {
        background-color: #e0f2fe;
        color: #0369a1;
        padding: 2px 8px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.75rem;
    }
</style>

<div class="container-fluid p-4" style="background: #f8f9fc; min-height: 100vh;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Matriks Keputusan (Penilaian Alternatif)</h4>
            <p class="text-muted small mb-0">Petakan bobot penilaian sub-kriteria untuk setiap alternatif jalur dan biaya.</p>
        </div>
        <button class="btn btn-primary shadow-sm px-4 py-2" style="border-radius: 10px; font-weight: 600;" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg me-2"></i>Tambah Penilaian
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card assessment-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle assessment-table">
                    <thead class="text-white">
                        <tr>
                            <th class="ps-4 text-center" width="5%">No</th>
                            <th class="ps-3">Alternatif Rute dan Armada</th>
                            @foreach($kriterias as $k)
                                <th class="text-center" style="min-width: 140px;">
                                    <div class="fw-bold">{{ $k->kode_kriteria }}</div>
                                    <div class="text-muted small fw-normal" style="font-size: 0.7rem; color: #94a3b8 !important;">{{ $k->nama_kriteria }}</div>
                                </th>
                            @endforeach
                            <th class="text-center" width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $grouped = $penilaians->groupBy(fn($i) => $i->jalur_id . '-' . $i->biaya_id); @endphp
                        @forelse($grouped as $key => $items)
                        @php 
                            $first = $items->first(); 
                            $modalId = "modalEdit" . $first->jalur_id . "_" . $first->biaya_id;
                        @endphp
                        <tr>
                            <td class="text-center fw-semibold text-secondary ps-4">{{ $loop->iteration }}</td>
                            
                            {{-- Alternatif Cell --}}
                            <td class="ps-3 alternative-cell">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark fs-6">
                                        <i class="bi bi-mountains me-2 text-success"></i>Gn. {{ $first->jalur->gunung->nama_gunung ?? '-' }}
                                    </span>
                                    <span class="text-muted small mb-2 ms-4">Jalur: {{ $first->jalur->nama_jalur ?? '-' }}</span>
                                    <span class="text-secondary small ms-4">
                                        <i class="bi bi-bus-front me-1 text-primary"></i>{{ $first->biaya->nama_armada ?? '-' }}
                                        <span class="text-muted" style="font-size: 0.75rem;">
                                            ({{ $first->biaya->start_terminal->nama_terminal ?? '-' }} &rarr; {{ $first->biaya->end_terminal->nama_terminal ?? '-' }})
                                        </span>
                                    </span>
                                </div>
                            </td>

                            {{-- Dynamic Kriteria Cells C1 - C6 with merged Actual Value & Score Badge --}}
                            @foreach($kriterias as $k)
                                @php 
                                    $penilaianItem = $items->where('kriteria_id', $k->id)->first();
                                    $nilaiSkor = $penilaianItem->nilai ?? 0;
                                    
                                    // Determine the styling class for the badge
                                    $badgeClass = 'score-badge-' . ($nilaiSkor >= 1 && $nilaiSkor <= 5 ? $nilaiSkor : '1');
                                    
                                    // Extract actual parameter values dynamically
                                    $actualVal = '-';
                                    switch($k->kode_kriteria) {
                                        case 'C1':
                                            $actualVal = 'Wd: Rp ' . number_format($first->jalur->biaya_simaksi_weekday ?? 0, 0, ',', '.') . ' / We: Rp ' . number_format($first->jalur->biaya_simaksi_weekend ?? 0, 0, ',', '.');
                                            break;
                                        case 'C2':
                                            $actualVal = 'Rp ' . number_format($first->biaya->harga_pp ?? 0, 0, ',', '.');
                                            break;
                                        case 'C3':
                                            $actualVal = number_format($first->jalur->gunung->ketinggian ?? 0, 0, ',', '.') . ' Mdpl';
                                            break;
                                        case 'C4':
                                            $actualVal = $first->jalur->tingkat_kesulitan ?? '-';
                                            break;
                                        case 'C5':
                                            $actualVal = ($first->biaya->estimasi_perjalanan ?? '-') . ' Jam';
                                            break;
                                        case 'C6':
                                            $actualVal = ($first->jalur->estimasi_jam ?? '-') . ' Jam';
                                            break;
                                    }
                                @endphp
                                <td class="text-center kriteria-cell">
                                    <div class="d-flex flex-column align-items-center">
                                        <span class="text-dark small fw-semibold">{{ $actualVal }}</span>
                                        <span class="score-badge {{ $badgeClass }}">
                                            Skor: {{ $nilaiSkor }}
                                        </span>
                                    </div>
                                </td>
                            @endforeach

                            {{-- Action buttons --}}
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary" style="border-radius: 8px;" data-bs-toggle="modal" data-bs-target="#{{ $modalId }}" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <form action="{{ route('admin.penilaian.destroy', ['jalur' => $first->jalur_id, 'biaya' => $first->biaya_id]) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: 8px;" onclick="return confirm('Apakah Anda yakin ingin menghapus seluruh penilaian alternatif rute ini?')" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- MODAL EDIT --}}
                        <div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content modal-premium">
                                    <div class="modal-header bg-warning text-dark">
                                        <div>
                                            <h5 class="modal-title fw-bold m-0"><i class="bi bi-pencil-square me-2"></i>Edit Penilaian Alternatif</h5>
                                            <small class="text-dark opacity-75">Gn. {{ $first->jalur->gunung->nama_gunung ?? '' }} - Via {{ $first->jalur->nama_jalur ?? '' }} | {{ $first->biaya->nama_armada ?? '' }}</small>
                                        </div>
                                        <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('admin.penilaian.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="jalur_id" value="{{ $first->jalur_id }}">
                                        <input type="hidden" name="biaya_id" value="{{ $first->biaya_id }}">
                                        <div class="modal-body">
                                            <p class="text-muted small mb-4">Tentukan skor bobot sub-kriteria yang sesuai untuk alternatif ini pada masing-masing kriteria di bawah. Nilai aktual parameter alternatif ditampilkan di sebelah label kriteria.</p>
                                            <div class="row g-4">
                                                @foreach($kriterias as $k)
                                                @php 
                                                    $nilaiLama = $items->where('kriteria_id', $k->id)->first()->nilai ?? ''; 
                                                    
                                                    // Map edit values
                                                    $editVal = '';
                                                    switch($k->kode_kriteria) {
                                                        case 'C1':
                                                            $editVal = 'Wd: Rp ' . number_format($first->jalur->biaya_simaksi_weekday ?? 0, 0, ',', '.') . ' / We: Rp ' . number_format($first->jalur->biaya_simaksi_weekend ?? 0, 0, ',', '.');
                                                            break;
                                                        case 'C2':
                                                            $editVal = 'Rp ' . number_format($first->biaya->harga_pp ?? 0, 0, ',', '.');
                                                            break;
                                                        case 'C3':
                                                            $editVal = number_format($first->jalur->gunung->ketinggian ?? 0, 0, ',', '.') . ' Mdpl';
                                                            break;
                                                        case 'C4':
                                                            $editVal = $first->jalur->tingkat_kesulitan ?? '-';
                                                            break;
                                                        case 'C5':
                                                            $editVal = ($first->biaya->estimasi_perjalanan ?? '-') . ' Jam';
                                                            break;
                                                        case 'C6':
                                                            $editVal = ($first->jalur->estimasi_jam ?? '-') . ' Jam';
                                                            break;
                                                    }
                                                @endphp
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold text-dark mb-1 d-flex justify-content-between align-items-center" style="font-size: 0.85rem;">
                                                        <span>{{ $k->kode_kriteria }} - {{ $k->nama_kriteria }}</span>
                                                        <span class="val-helper">{{ $editVal }}</span>
                                                    </label>
                                                    <select name="nilai[{{ $k->id }}]" class="form-select form-select-custom" required>
                                                        @foreach($k->subKriterias as $sub)
                                                            <option value="{{ $sub->bobot }}" {{ $nilaiLama == $sub->bobot ? 'selected' : '' }}>
                                                                {{ $sub->nama_sub }} (Skor: {{ $sub->bobot }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary px-4 py-2" style="border-radius: 10px;" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-warning px-4 py-2 fw-bold text-dark" style="border-radius: 10px;">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modal-premium">
            <div class="modal-header bg-primary text-white">
                <div>
                    <h5 class="modal-title fw-bold m-0"><i class="bi bi-plus-circle me-2"></i>Input Penilaian Matriks Baru</h5>
                    <small class="text-white opacity-75">Hubungkan rute pendakian dengan bus dan tentukan bobot kriterianya.</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.penilaian.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-4 mb-4 pb-3 border-bottom">
                        {{-- Custom Searchable Select: Rute Gunung --}}
                        <div class="col-md-6 position-relative">
                            <label class="form-label fw-bold text-dark mb-1">Pilih Rute Gunung (Jalur)</label>
                            <input type="hidden" name="jalur_id" id="selected-jalur-id" required>
                            
                            <button type="button" class="btn dropdown-btn-custom text-start d-flex justify-content-between align-items-center w-100" id="jalur-dropdown-btn">
                                <span id="jalur-dropdown-label" class="text-muted small">-- Pilih Rute Gunung --</span>
                                <i class="bi bi-chevron-down text-secondary"></i>
                            </button>
                            
                            <div class="custom-dropdown-menu d-none position-absolute bg-white shadow-lg border rounded-3 p-3 mt-1" id="jalur-dropdown-menu" style="z-index: 1050; left: 12px; right: 12px; max-height: 350px; overflow-y: auto;">
                                <input type="text" class="form-control mb-3 search-input" placeholder="Cari gunung atau jalur...">
                                <div class="dropdown-list-items">
                                    @foreach($jalurs as $j)
                                        @php
                                            // Handle "Gunung" keyword duplication cleaner: if name contains Gunung already, don't duplicate
                                            $cleanName = str_ireplace('Gunung ', '', $j->gunung->nama_gunung);
                                        @endphp
                                        <div class="dropdown-item-card p-3 mb-2 rounded-3" 
                                             data-id="{{ $j->id }}" 
                                             data-search="gn {{ strtolower($cleanName) }} {{ strtolower($j->nama_jalur) }}"
                                             data-ketinggian="{{ number_format($j->gunung->ketinggian, 0, ',', '.') }} Mdpl"
                                             data-kesulitan="{{ $j->tingkat_kesulitan }}"
                                             data-estimasi="{{ $j->estimasi_jam }} Jam"
                                             data-simaksi="Wd: Rp {{ number_format($j->biaya_simaksi_weekday, 0, ',', '.') }} / We: Rp {{ number_format($j->biaya_simaksi_weekend, 0, ',', '.') }}"
                                             data-gunung="Gn. {{ $cleanName }}"
                                             data-jalur="Jalur {{ $j->nama_jalur }}">
                                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                                <div>
                                                    <span class="fw-bold text-dark d-block">Gn. {{ $cleanName }}</span>
                                                    <span class="text-muted small">Jalur {{ $j->nama_jalur }}</span>
                                                </div>
                                                <div class="d-flex flex-wrap gap-1 justify-content-end">
                                                    <span class="badge bg-light text-dark border" style="font-size: 0.7rem;">{{ number_format($j->gunung->ketinggian, 0, ',', '.') }} Mdpl</span>
                                                    @php
                                                        $diffBg = $j->tingkat_kesulitan == 'Sulit' ? 'bg-danger-subtle text-danger' : ($j->tingkat_kesulitan == 'Sedang' ? 'bg-warning-subtle text-warning' : 'bg-success-subtle text-success');
                                                    @endphp
                                                    <span class="badge {{ $diffBg }}" style="font-size: 0.7rem;">{{ $j->tingkat_kesulitan }}</span>
                                                    <span class="badge bg-info-subtle text-info" style="font-size: 0.7rem;">{{ $j->estimasi_jam }} Jam</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Custom Searchable Select: Armada Bus --}}
                        <div class="col-md-6 position-relative">
                            <label class="form-label fw-bold text-dark mb-1">Pilih Armada Bus & Tarif</label>
                            <input type="hidden" name="biaya_id" id="selected-biaya-id" required>
                            
                            <button type="button" class="btn dropdown-btn-custom text-start d-flex justify-content-between align-items-center w-100" id="biaya-dropdown-btn">
                                <span id="biaya-dropdown-label" class="text-muted small">-- Pilih Armada Bus --</span>
                                <i class="bi bi-chevron-down text-secondary"></i>
                            </button>
                            
                            <div class="custom-dropdown-menu d-none position-absolute bg-white shadow-lg border rounded-3 p-3 mt-1" id="biaya-dropdown-menu" style="z-index: 1050; left: 12px; right: 12px; max-height: 350px; overflow-y: auto;">
                                <input type="text" class="form-control mb-3 search-input" placeholder="Cari armada atau terminal...">
                                <div class="dropdown-list-items">
                                    @foreach($biayas as $b)
                                        @php
                                            $cleanStart = str_ireplace('Terminal ', '', $b->start_terminal->nama_terminal);
                                            $cleanEnd = str_ireplace('Terminal ', '', $b->end_terminal->nama_terminal);
                                        @endphp
                                        <div class="dropdown-item-card p-3 mb-2 rounded-3" 
                                             data-id="{{ $b->id }}" 
                                             data-jalur-id="{{ $b->jalur_id }}"
                                             data-search="{{ strtolower($b->nama_armada) }} {{ strtolower($cleanStart) }} {{ strtolower($cleanEnd) }} {{ strtolower($b->jalur->gunung->nama_gunung ?? '') }} {{ strtolower($b->jalur->nama_jalur ?? '') }}"
                                             data-harga="Rp {{ number_format($b->harga_pp, 0, ',', '.') }}"
                                             data-estimasi="{{ $b->estimasi_perjalanan }} Jam"
                                             data-armada="{{ $b->nama_armada }}"
                                             data-start="{{ $cleanStart }}"
                                             data-end="{{ $cleanEnd }}">
                                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                                <div>
                                                    <span class="fw-bold text-dark d-block">{{ $b->nama_armada }}</span>
                                                    <span class="text-muted small" style="font-size: 0.75rem;">
                                                        {{ $cleanStart }} &rarr; {{ $cleanEnd }}
                                                    </span>
                                                    @if($b->jalur)
                                                        <span class="d-block text-primary small fw-semibold mt-1" style="font-size: 0.72rem;">
                                                            <i class="bi bi-geo-alt-fill me-1"></i>Untuk: Gn. {{ $b->jalur->gunung->nama_gunung ?? '-' }} ({{ $b->jalur->nama_jalur ?? '-' }})
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="d-flex flex-wrap gap-1 justify-content-end align-items-center">
                                                    <span class="badge bg-success text-white fw-bold" style="font-size: 0.75rem;">Rp {{ number_format($b->harga_pp, 0, ',', '.') }}</span>
                                                    <span class="badge bg-light text-dark border" style="font-size: 0.7rem;">{{ $b->estimasi_perjalanan }} Jam</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-list-check me-2 text-primary"></i>Penilaian Sub-Kriteria</h6>
                    <div class="row g-4">
                        @foreach($kriterias as $k)
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark mb-1 d-flex justify-content-between align-items-center" style="font-size: 0.85rem;">
                                <span>{{ $k->kode_kriteria }} - {{ $k->nama_kriteria }}</span>
                                <span id="val-{{ $k->kode_kriteria }}" class="val-helper d-none"></span>
                            </label>
                            <select name="nilai[{{ $k->id }}]" class="form-select form-select-custom" required>
                                <option value="" disabled selected>-- Pilih Skor Penilaian --</option>
                                @foreach($k->subKriterias as $sub)
                                    <option value="{{ $sub->bobot }}">{{ $sub->nama_sub }} (Skor: {{ $sub->bobot }})</option>
                                @endforeach
                            </select>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-4 py-2" style="border-radius: 10px;" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-bold" style="border-radius: 10px;">Simpan Penilaian</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Array of existing rated combinations
        const existingAssessments = [
            @foreach($grouped as $key => $items)
                '{{ $key }}',
            @endforeach
        ];

        function updateDropdownStates() {
            const selectedJalurId = document.getElementById('selected-jalur-id').value;

            // Update Jalur dropdown items: hide if already has ANY assessment
            const jalurItems = document.querySelectorAll('#jalur-dropdown-menu .dropdown-item-card');
            jalurItems.forEach(item => {
                const jId = item.getAttribute('data-id');
                const hasAnyAssessment = existingAssessments.some(k => k.startsWith(jId + '-'));
                
                if (hasAnyAssessment) {
                    item.style.setProperty('display', 'none', 'important');
                } else {
                    item.style.setProperty('display', 'block', 'important');
                }
            });

            // Update Biaya dropdown items: hide if combination is already assessed or doesn't match selectedJalurId
            const biayaItems = document.querySelectorAll('#biaya-dropdown-menu .dropdown-item-card');
            biayaItems.forEach(item => {
                const bId = item.getAttribute('data-id');
                const bJalurId = item.getAttribute('data-jalur-id');
                const key = bJalurId + '-' + bId;

                // Hide if already assessed
                if (existingAssessments.includes(key)) {
                    item.style.setProperty('display', 'none', 'important');
                    return;
                }

                // Filter by selectedJalurId
                if (selectedJalurId) {
                    if (bJalurId !== selectedJalurId) {
                        item.style.setProperty('display', 'none', 'important');
                    } else {
                        item.style.setProperty('display', 'block', 'important');
                    }
                } else {
                    item.style.setProperty('display', 'block', 'important');
                }
            });
        }

        // Custom Dropdown Search & Select Logic
        function initCustomDropdown(type) {
            const btn = document.getElementById(type + '-dropdown-btn');
            const label = document.getElementById(type + '-dropdown-label');
            const menu = document.getElementById(type + '-dropdown-menu');
            const hiddenInput = document.getElementById('selected-' + type + '-id');
            const searchInput = menu.querySelector('.search-input');
            const items = menu.querySelectorAll('.dropdown-item-card');

            // Toggle menu visibility
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                menu.classList.toggle('d-none');
                if (!menu.classList.contains('d-none')) {
                    searchInput.focus();
                }
            });

            // Hide menu when clicking outside
            document.addEventListener('click', function(e) {
                if (!btn.contains(e.target) && !menu.contains(e.target)) {
                    menu.classList.add('d-none');
                }
            });

            // Handle item selection
            items.forEach(function(item) {
                item.addEventListener('click', function() {
                    // Clear selection
                    items.forEach(i => i.classList.remove('selected'));
                    
                    // Set selected state
                    item.classList.add('selected');
                    
                    // Set hidden input value
                    hiddenInput.value = item.getAttribute('data-id');
                    
                    // Update button text
                    const titleText = item.querySelector('.fw-bold').textContent;
                    const subText = item.querySelector('.text-muted').textContent;
                    label.innerHTML = `<strong>${titleText}</strong> <span class="text-secondary small ms-2">(${subText})</span>`;
                    label.classList.remove('text-muted');

                    // Update inline value helpers next to sub-criteria dropdowns
                    if (type === 'jalur') {
                        // Clear selected biaya if it doesn't match the new jalur selection
                        const selectedBiayaInput = document.getElementById('selected-biaya-id');
                        const selectedBiayaId = selectedBiayaInput.value;
                        if (selectedBiayaId) {
                            const currentSelectedBiaya = document.querySelector(`#biaya-dropdown-menu .dropdown-item-card[data-id="${selectedBiayaId}"]`);
                            if (currentSelectedBiaya) {
                                const bJalurId = currentSelectedBiaya.getAttribute('data-jalur-id');
                                if (bJalurId !== hiddenInput.value) {
                                    // Reset biaya selection
                                    selectedBiayaInput.value = '';
                                    const biayaLabel = document.getElementById('biaya-dropdown-label');
                                    biayaLabel.innerHTML = '-- Pilih Armada Bus --';
                                    biayaLabel.classList.add('text-muted');
                                    currentSelectedBiaya.classList.remove('selected');
                                    
                                    // Hide helpers C2 and C5
                                    const valC2 = document.getElementById('val-C2');
                                    if (valC2) valC2.classList.add('d-none');
                                    const valC5 = document.getElementById('val-C5');
                                    if (valC5) valC5.classList.add('d-none');
                                }
                            }
                        }

                        // C1: Biaya Simaksi
                        const valC1 = document.getElementById('val-C1');
                        valC1.textContent = item.getAttribute('data-simaksi');
                        valC1.classList.remove('d-none');

                        // C3: Ketinggian Gunung
                        const valC3 = document.getElementById('val-C3');
                        valC3.textContent = item.getAttribute('data-ketinggian');
                        valC3.classList.remove('d-none');

                        // C4: Tingkat Kesulitan
                        const valC4 = document.getElementById('val-C4');
                        valC4.textContent = item.getAttribute('data-kesulitan');
                        valC4.classList.remove('d-none');

                        // C6: Estimasi Waktu Pendakian
                        const valC6 = document.getElementById('val-C6');
                        valC6.textContent = item.getAttribute('data-estimasi');
                        valC6.classList.remove('d-none');
                    } else if (type === 'biaya') {
                        // C2: Biaya Transportasi
                        const valC2 = document.getElementById('val-C2');
                        valC2.textContent = item.getAttribute('data-harga');
                        valC2.classList.remove('d-none');

                        // C5: Estimasi Waktu Perjalanan Bus
                        const valC5 = document.getElementById('val-C5');
                        valC5.textContent = item.getAttribute('data-estimasi');
                        valC5.classList.remove('d-none');
                    }

                    // Close menu
                    menu.classList.add('d-none');

                    // Recalculate dropdown states to disable duplicates
                    updateDropdownStates();
                });
            });

            // Handle search filtering
            searchInput.addEventListener('input', function() {
                const query = searchInput.value.toLowerCase().trim();
                const selectedJalurId = document.getElementById('selected-jalur-id').value;
                items.forEach(function(item) {
                    const searchData = item.getAttribute('data-search');
                    
                    if (type === 'jalur') {
                        const jId = item.getAttribute('data-id');
                        const hasAnyAssessment = existingAssessments.some(k => k.startsWith(jId + '-'));
                        if (hasAnyAssessment) {
                            item.style.setProperty('display', 'none', 'important');
                            return;
                        }
                    }

                    if (type === 'biaya') {
                        const bId = item.getAttribute('data-id');
                        const bJalurId = item.getAttribute('data-jalur-id');
                        const key = bJalurId + '-' + bId;

                        if (existingAssessments.includes(key)) {
                            item.style.setProperty('display', 'none', 'important');
                            return;
                        }

                        if (selectedJalurId && bJalurId !== selectedJalurId) {
                            item.style.setProperty('display', 'none', 'important');
                            return;
                        }
                    }

                    if (searchData.includes(query)) {
                        item.style.setProperty('display', 'block', 'important');
                    } else {
                        item.style.setProperty('display', 'none', 'important');
                    }
                });
            });
        }

        initCustomDropdown('jalur');
        initCustomDropdown('biaya');
        updateDropdownStates();

        // Form Submit Validation
        const form = document.querySelector('#modalTambah form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const jalurId = document.getElementById('selected-jalur-id').value;
                const biayaId = document.getElementById('selected-biaya-id').value;
                
                if (!jalurId) {
                    alert('Silakan pilih Rute Gunung terlebih dahulu!');
                    e.preventDefault();
                    return false;
                }
                if (!biayaId) {
                    alert('Silakan pilih Armada Bus & Tarif terlebih dahulu!');
                    e.preventDefault();
                    return false;
                }
            });
        }
    });
</script>
@endsection