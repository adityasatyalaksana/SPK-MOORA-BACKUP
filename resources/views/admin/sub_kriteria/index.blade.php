@extends('layouts.admin')

@section('content')
<!-- Google Fonts: Outfit -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    .subkriteria-body {
        font-family: 'Outfit', sans-serif;
        background-color: #f1f5f9;
        color: #1e293b;
    }

    .btn-premium-primary {
        background-color: #10b981 !important;
        border-color: #10b981 !important;
        color: #ffffff !important;
        border-radius: 12px;
        font-weight: 600;
        padding: 10px 24px;
        transition: all 0.2s ease;
    }
    .btn-premium-primary:hover {
        background-color: #059669 !important;
        border-color: #059669 !important;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15) !important;
    }

    .btn-premium-secondary {
        border-radius: 12px;
        font-weight: 600;
        padding: 10px 24px;
        background-color: #e2e8f0;
        border: none;
        color: #334155;
        transition: all 0.2s ease;
    }
    .btn-premium-secondary:hover {
        background-color: #cbd5e1;
        color: #1e293b;
    }

    .premium-card {
        border-radius: 16px;
        overflow: hidden;
        border: none;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.03);
        background: #ffffff;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .premium-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 25px rgba(15, 23, 42, 0.06);
    }

    .type-benefit {
        background-color: #ecfdf5 !important;
        color: #10b981 !important;
        border: 1px solid rgba(16, 185, 129, 0.2) !important;
    }
    .type-cost {
        background-color: #fffbeb !important;
        color: #f59e0b !important;
        border: 1px solid rgba(245, 158, 11, 0.2) !important;
    }
</style>

<div class="subkriteria-body container-fluid p-4">
    <!-- Success Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4 d-flex align-items-center" role="alert" style="background-color: #ecfdf5; border-left: 4px solid #10b981 !important; border-radius: 12px; color: #065f46;">
            <i class="bi bi-check-circle-fill me-3 fs-5" style="color: #10b981;"></i>
            <div class="fw-semibold">{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Data Sub-Kriteria</h3>
            <p class="text-muted small mb-0">Kelola bobot dan parameter nilai dari setiap kriteria utama MOORA.</p>
        </div>
        <button class="btn btn-premium-primary shadow-sm" id="global-add-btn" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg me-2"></i>Tambah Sub-Kriteria
        </button>
    </div>

    <!-- Grid Layout Kartu Per Kriteria Utama -->
    <div class="row row-cols-1 row-cols-lg-2 g-4">
        @foreach($kriterias as $k)
        <div class="col">
            <div class="card premium-card h-100 border-0 shadow-sm">
                <!-- Header Kartu Kriteria -->
                <div class="card-header bg-dark text-white p-3 d-flex justify-content-between align-items-center border-0">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-primary px-3 py-2 me-2 font-monospace" style="border-radius: 8px; font-size: 0.85rem;">{{ $k->kode_kriteria }}</span>
                        <span class="fw-bold fs-6">{{ $k->nama_kriteria }}</span>
                    </div>
                    <div class="d-flex align-items-center gap-1">
                        @php
                            $badgeClass = strtolower($k->tipe) === 'benefit' ? 'type-benefit' : 'type-cost';
                        @endphp
                        <span class="badge {{ $badgeClass }} px-2.5 py-1.5 rounded-3" style="font-size: 0.7rem; font-weight: 600;">
                            {{ $k->tipe }}
                        </span>
                        <span class="badge bg-light text-dark border px-2.5 py-1.5 rounded-3" style="font-size: 0.7rem; font-weight: 600;">
                            Bobot: {{ $k->bobot }}
                        </span>
                    </div>
                </div>

                <!-- Body Kartu: Tabel Sub-Kriteria -->
                <div class="card-body p-0 flex-grow-1">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small">
                                <tr>
                                    <th class="ps-3" width="60">No</th>
                                    <th>Parameter Sub-Kriteria</th>
                                    <th class="text-center" width="100">Bobot/Nilai</th>
                                    <th class="text-center" width="100">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($k->subKriterias as $index => $s)
                                <tr>
                                    <td class="ps-3 text-muted fw-bold">{{ $index + 1 }}</td>
                                    <td class="fw-bold text-dark">{{ $s->nama_sub }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary px-2.5 py-1.5 rounded-3 fw-bold" style="font-size: 0.8rem;">
                                            {{ $s->bobot }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <!-- Tombol Edit -->
                                            <button type="button" class="btn btn-sm btn-outline-primary" style="border-radius: 8px; padding: 4px 8px;" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $s->id }}" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            
                                            <!-- Form Hapus -->
                                            <form action="{{ route('sub-kriteria.destroy', $s->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: 8px; padding: 4px 8px;" onclick="return confirm('Apakah Anda yakin ingin menghapus sub-kriteria ini?')" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted small">
                                        <i class="bi bi-info-circle display-6 d-block mb-2 text-secondary"></i>
                                        Belum ada data sub-kriteria.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Footer Kartu: Pintasan Tambah Cepat -->
                <div class="card-footer bg-white border-0 p-3 text-end">
                    <button type="button" class="btn btn-sm btn-outline-primary btn-add-sub px-3" style="border-radius: 8px; font-weight: 600;" 
                            data-kriteria-id="{{ $k->id }}" 
                            data-kriteria-kode="{{ $k->kode_kriteria }}" 
                            data-kriteria-nama="{{ $k->nama_kriteria }}" 
                            data-bs-toggle="modal" 
                            data-bs-target="#modalTambah">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Sub
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- MODAL TAMBAH SUB-KRITERIA (Berada di luar agar valid secara HTML) --}}
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-premium">
            <div class="modal-header bg-light border-0 py-3">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center m-0">
                    <i class="bi bi-plus-circle-fill text-primary me-2 fs-4"></i> Tambah Sub-Kriteria Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('sub-kriteria.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 text-start">
                    <div class="mb-3 text-start position-relative">
                        <label class="form-label fw-bold small text-muted text-uppercase">Pilih Kriteria Utama</label>
                        <input type="hidden" name="kriteria_id" id="selected-add-kriteria-id" required>
                        
                        <button type="button" class="btn dropdown-btn-custom text-start d-flex justify-content-between align-items-center w-100" id="add-kriteria-dropdown-btn">
                            <span id="add-kriteria-dropdown-label" class="text-muted small">-- Pilih Kriteria --</span>
                            <i class="bi bi-chevron-down text-secondary"></i>
                        </button>
                        
                        <div class="custom-dropdown-menu d-none position-absolute bg-white shadow-lg border rounded-3 p-3 mt-1" id="add-kriteria-dropdown-menu" style="z-index: 1050; left: 12px; right: 12px; max-height: 250px; overflow-y: auto;">
                            <input type="text" class="form-control mb-3 search-input" placeholder="Cari kriteria...">
                            <div class="dropdown-list-items">
                                @foreach($kriterias as $k)
                                    <div class="dropdown-item-card p-3 mb-2 rounded-3" 
                                         data-id="{{ $k->id }}" 
                                         data-search="{{ strtolower($k->kode_kriteria) }} {{ strtolower($k->nama_kriteria) }}"
                                         data-kode="{{ $k->kode_kriteria }}"
                                         data-nama="{{ $k->nama_kriteria }}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="badge bg-dark text-white me-2">{{ $k->kode_kriteria }}</span>
                                                <span class="fw-bold text-dark">{{ $k->nama_kriteria }}</span>
                                            </div>
                                            <div>
                                                <span class="badge border border-info text-info" style="font-size: 0.72rem;">{{ $k->tipe }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Nama Sub-Kriteria</label>
                        <input type="text" name="nama_sub" class="form-control form-control-premium" required placeholder="Contoh: Murah / Sulit / Sangat Dekat">
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold small text-muted text-uppercase">Bobot Nilai (Angka)</label>
                        <input type="number" name="bobot" class="form-control form-control-premium" required placeholder="Contoh: 1-5">
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 py-3 px-4">
                    <button type="button" class="btn btn-premium-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-premium-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT SUB-KRITERIA LOOP (Berada di luar struktur tabel/card agar valid secara HTML) --}}
@foreach($kriterias as $k)
    @foreach($k->subKriterias as $s)
    <div class="modal fade" id="modalEdit{{ $s->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-premium">
                <div class="modal-header bg-light border-0 py-3">
                    <h5 class="modal-title fw-bold text-dark d-flex align-items-center m-0">
                        <i class="bi bi-pencil-square text-info me-2 fs-4"></i> Edit Sub-Kriteria
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('sub-kriteria.update', $s->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4 text-start">
                        <div class="mb-3 text-start position-relative">
                            <label class="form-label fw-bold small text-muted text-uppercase">Kriteria Utama</label>
                            <input type="hidden" name="kriteria_id" id="selected-edit-kriteria-id-{{ $s->id }}" value="{{ $s->kriteria_id }}" required>
                            
                            <button type="button" class="btn dropdown-btn-custom text-start d-flex justify-content-between align-items-center w-100 edit-kriteria-dropdown-btn" id="edit-kriteria-dropdown-btn-{{ $s->id }}" data-id="{{ $s->id }}">
                                <span id="edit-kriteria-dropdown-label-{{ $s->id }}" class="text-dark fw-bold">
                                    {{ $s->kriteria->kode_kriteria }} - {{ $s->kriteria->nama_kriteria }}
                                </span>
                                <i class="bi bi-chevron-down text-secondary"></i>
                            </button>
                            
                            <div class="custom-dropdown-menu d-none position-absolute bg-white shadow-lg border rounded-3 p-3 mt-1 edit-kriteria-dropdown-menu" id="edit-kriteria-dropdown-menu-{{ $s->id }}" style="z-index: 1050; left: 12px; right: 12px; max-height: 250px; overflow-y: auto;">
                                <input type="text" class="form-control mb-3 search-input" placeholder="Cari kriteria...">
                                <div class="dropdown-list-items">
                                    @foreach($kriterias as $k_opt)
                                        <div class="dropdown-item-card p-3 mb-2 rounded-3 {{ $s->kriteria_id == $k_opt->id ? 'selected' : '' }}" 
                                             data-id="{{ $k_opt->id }}" 
                                             data-search="{{ strtolower($k_opt->kode_kriteria) }} {{ strtolower($k_opt->nama_kriteria) }}"
                                             data-kode="{{ $k_opt->kode_kriteria }}"
                                             data-nama="{{ $k_opt->nama_kriteria }}">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <span class="badge bg-dark text-white me-2">{{ $k_opt->kode_kriteria }}</span>
                                                    <span class="fw-bold text-dark">{{ $k_opt->nama_kriteria }}</span>
                                                </div>
                                                <div>
                                                    <span class="badge border border-info text-info" style="font-size: 0.72rem;">{{ $k_opt->tipe }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Nama Sub-Kriteria</label>
                            <input type="text" name="nama_sub" class="form-control form-control-premium" value="{{ $s->nama_sub }}" required placeholder="Contoh: Sangat Sulit">
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold small text-muted text-uppercase">Bobot Nilai (Angka)</label>
                            <input type="number" name="bobot" class="form-control form-control-premium" value="{{ $s->bobot }}" required placeholder="Contoh: 5">
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0 py-3 px-4">
                        <button type="button" class="btn btn-premium-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-premium-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
@endforeach

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Searchable Dropdown for Add Sub Kriteria Modal
        function initAddKriteriaDropdown() {
            const btn = document.getElementById('add-kriteria-dropdown-btn');
            const label = document.getElementById('add-kriteria-dropdown-label');
            const menu = document.getElementById('add-kriteria-dropdown-menu');
            const hiddenInput = document.getElementById('selected-add-kriteria-id');
            if (!btn || !menu) return;
            const searchInput = menu.querySelector('.search-input');
            const items = menu.querySelectorAll('.dropdown-item-card');

            // Toggle menu
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                menu.classList.toggle('d-none');
                if (!menu.classList.contains('d-none')) {
                    searchInput.focus();
                }
            });

            // Select item
            items.forEach(function(item) {
                item.addEventListener('click', function() {
                    items.forEach(i => i.classList.remove('selected'));
                    item.classList.add('selected');
                    hiddenInput.value = item.getAttribute('data-id');
                    
                    const kode = item.getAttribute('data-kode');
                    const nama = item.getAttribute('data-nama');
                    label.innerHTML = `<strong>${kode} - ${nama}</strong>`;
                    label.classList.remove('text-muted');
                    menu.classList.add('d-none');
                });
            });

            // Search filtering
            searchInput.addEventListener('input', function() {
                const query = searchInput.value.toLowerCase().trim();
                items.forEach(function(item) {
                    const searchData = item.getAttribute('data-search');
                    if (searchData.includes(query)) {
                        item.style.setProperty('display', 'block', 'important');
                    } else {
                        item.style.setProperty('display', 'none', 'important');
                    }
                });
            });
        }
        initAddKriteriaDropdown();

        // Searchable Dropdowns for Edit Sub Kriteria Modals
        function initEditKriteriaDropdowns() {
            document.querySelectorAll('.edit-kriteria-dropdown-btn').forEach(function(btn) {
                const id = btn.getAttribute('data-id');
                const label = document.getElementById('edit-kriteria-dropdown-label-' + id);
                const menu = document.getElementById('edit-kriteria-dropdown-menu-' + id);
                const hiddenInput = document.getElementById('selected-edit-kriteria-id-' + id);
                if (!btn || !menu) return;
                const searchInput = menu.querySelector('.search-input');
                const items = menu.querySelectorAll('.dropdown-item-card');

                // Toggle menu
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    // Close other menus first
                    document.querySelectorAll('.edit-kriteria-dropdown-menu').forEach(function(otherMenu) {
                        if (otherMenu !== menu) otherMenu.classList.add('d-none');
                    });
                    menu.classList.toggle('d-none');
                    if (!menu.classList.contains('d-none')) {
                        searchInput.focus();
                    }
                });

                // Select item
                items.forEach(function(item) {
                    item.addEventListener('click', function() {
                        items.forEach(i => i.classList.remove('selected'));
                        item.classList.add('selected');
                        hiddenInput.value = item.getAttribute('data-id');
                        
                        const kode = item.getAttribute('data-kode');
                        const nama = item.getAttribute('data-nama');
                        label.innerHTML = `<strong>${kode} - ${nama}</strong>`;
                        label.classList.remove('text-muted');
                        menu.classList.add('d-none');
                    });
                });

                // Search filtering
                searchInput.addEventListener('input', function() {
                    const query = searchInput.value.toLowerCase().trim();
                    items.forEach(function(item) {
                        const searchData = item.getAttribute('data-search');
                        if (searchData.includes(query)) {
                            item.style.setProperty('display', 'block', 'important');
                        } else {
                            item.style.setProperty('display', 'none', 'important');
                        }
                    });
                });
            });
        }
        initEditKriteriaDropdowns();

        // Global Event Listener to close all dropdowns when click outside
        document.addEventListener('click', function(e) {
            document.querySelectorAll('.custom-dropdown-menu').forEach(function(menu) {
                const container = menu.parentElement;
                const btn = container.querySelector('.dropdown-btn-custom, .edit-kriteria-dropdown-btn');
                if (btn && !btn.contains(e.target) && !menu.contains(e.target)) {
                    menu.classList.add('d-none');
                }
            });
        });

        // Add preselect listeners for card buttons
        document.querySelectorAll('.btn-add-sub').forEach(btn => {
            btn.addEventListener('click', function() {
                const kId = this.getAttribute('data-kriteria-id');
                const kKode = this.getAttribute('data-kriteria-kode');
                const kNama = this.getAttribute('data-kriteria-nama');
                
                document.getElementById('selected-add-kriteria-id').value = kId;
                const label = document.getElementById('add-kriteria-dropdown-label');
                label.innerHTML = `<strong>${kKode} - ${kNama}</strong>`;
                label.classList.remove('text-muted');
                
                // Mark selection in the item card list
                const items = document.querySelectorAll('#add-kriteria-dropdown-menu .dropdown-item-card');
                items.forEach(i => {
                    if (i.getAttribute('data-id') == kId) {
                        i.classList.add('selected');
                    } else {
                        i.classList.remove('selected');
                    }
                });
            });
        });

        // Global add button listener to reset selections
        const globalAddBtn = document.getElementById('global-add-btn');
        if (globalAddBtn) {
            globalAddBtn.addEventListener('click', function() {
                document.getElementById('selected-add-kriteria-id').value = '';
                const label = document.getElementById('add-kriteria-dropdown-label');
                label.innerHTML = '-- Pilih Kriteria --';
                label.classList.add('text-muted');
                
                const items = document.querySelectorAll('#add-kriteria-dropdown-menu .dropdown-item-card');
                items.forEach(i => i.classList.remove('selected'));
            });
        }

        // Validation for Add form submit
        const addForm = document.querySelector('#modalTambah form');
        if (addForm) {
            addForm.addEventListener('submit', function(e) {
                const selectedKriteria = document.getElementById('selected-add-kriteria-id').value;
                if (!selectedKriteria) {
                    alert('Silakan pilih Kriteria terlebih dahulu!');
                    e.preventDefault();
                    return false;
                }
            });
        }
    });
</script>
@endsection