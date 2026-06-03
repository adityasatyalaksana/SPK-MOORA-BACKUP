@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark">Data Sub-Kriteria</h3>
            <p class="text-muted small">Kelola parameter nilai untuk setiap kriteria MOORA.</p>
        </div>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg me-2"></i>Tambah Sub-Kriteria
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
    @endif

    <div class="card premium-card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle premium-table">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th class="ps-4" width="10%">No</th>
                            <th width="25%">Kriteria Utama</th>
                            <th width="35%">Nama Sub-Kriteria</th>
                            <th width="15%" class="text-center">Bobot/Nilai</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($subkriterias as $key => $s)
                        <tr>
                            <td class="ps-4 text-muted">{{ $key + 1 }}</td>
                            <td>
                                <span class="badge bg-primary px-2.5 py-1.5" style="border-radius: 6px;">
                                    {{ $s->kriteria->kode_kriteria }} - {{ $s->kriteria->nama_kriteria }}
                                </span>
                            </td>
                            <td class="fw-bold">{{ $s->nama_sub }}</td>
                            <td class="text-center">
                                <span class="badge bg-dark px-2.5 py-1.5" style="border-radius: 6px;">{{ $s->bobot }}</span>
                            </td>
                             <td class="text-center">
                                  <div class="d-flex justify-content-center gap-2">
                                      <button type="button" class="btn btn-sm btn-outline-primary" style="border-radius: 8px;" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $s->id }}" title="Edit">
                                          <i class="bi bi-pencil-square"></i>
                                      </button>
                                      <form action="{{ route('sub-kriteria.destroy', $s->id) }}" method="POST">
                                          @csrf
                                          @method('DELETE')
                                          <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: 8px;" onclick="return confirm('Hapus data ini?')" title="Hapus">
                                              <i class="bi bi-trash"></i>
                                          </button>
                                      </form>
                                  </div>
                             </td>
                        </tr>

                        {{-- MODAL EDIT --}}
                        <div class="modal fade" id="modalEdit{{ $s->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content modal-premium">
                                    <div class="modal-header bg-light">
                                        <h5 class="modal-title fw-bold text-dark d-flex align-items-center">
                                            <i class="bi bi-pencil-square text-info me-2" style="font-size: 1.25rem;"></i>Edit Sub-Kriteria
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('sub-kriteria.update', $s->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body p-4 text-start">
                                            <div class="mb-3 text-start position-relative">
                                                <label class="form-label fw-bold small text-muted text-uppercase">Pilih Kriteria Utama</label>
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
                                                        @foreach($kriterias as $k)
                                                            <div class="dropdown-item-card p-3 mb-2 rounded-3 {{ $s->kriteria_id == $k->id ? 'selected' : '' }}" 
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
                                                <input type="text" name="nama_sub" class="form-control form-control-premium" value="{{ $s->nama_sub }}" required placeholder="Contoh: Sangat Sulit">
                                            </div>
                                            <div class="mb-0">
                                                <label class="form-label fw-bold small text-muted text-uppercase">Bobot Nilai (Angka)</label>
                                                <input type="number" name="bobot" class="form-control form-control-premium" value="{{ $s->bobot }}" required placeholder="Contoh: 5">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-premium-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-info btn-premium-primary text-white fw-bold">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-folder2-open d-block mb-2" style="font-size: 2rem;"></i>
                                Belum ada data sub-kriteria.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-premium">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center">
                    <i class="bi bi-plus-circle-fill text-primary me-2" style="font-size: 1.25rem;"></i>Tambah Sub-Kriteria Baru
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-premium-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-premium-primary fw-bold">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

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

            // Close when click outside
            document.addEventListener('click', function(e) {
                if (!btn.contains(e.target) && !menu.contains(e.target)) {
                    menu.classList.add('d-none');
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

                // Close when click outside
                document.addEventListener('click', function(e) {
                    if (!btn.contains(e.target) && !menu.contains(e.target)) {
                        menu.classList.add('d-none');
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