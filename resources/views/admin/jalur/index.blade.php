@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark">Master Data Jalur</h3>
            <p class="text-muted small">Kelola jalur pendakian tanpa desimal.</p>
        </div>
        <button type="button" class="btn btn-primary btn-premium-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahJalur">
            <i class="bi bi-plus-lg me-2"></i> Tambah Jalur
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-4">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card premium-card shadow-sm">
        <div class="card-body p-0"> {{-- Mengurangi padding agar tabel lebih luas --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 premium-table">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th class="ps-4" width="50">No</th>
                            <th>Gunung</th>
                            <th>Nama Jalur</th>
                            <th>Simaksi (Wd / We)</th>
                            <th>Estimasi</th>
                            <th>Kesulitan</th>
                            <th class="text-center" width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jalurs as $key => $item)
                        <tr>
                            <td class="ps-4">{{ $key + 1 }}</td>
                            <td class="fw-bold text-primary">{{ $item->gunung->nama_gunung }}</td>
                            <td>{{ $item->nama_jalur }}</td>
                            <td>
                                <span class="d-block fw-semibold text-dark" style="font-size: 0.85rem;">Wd: Rp {{ number_format($item->biaya_simaksi_weekday, 0, ',', '.') }}</span>
                                <span class="d-block small text-muted" style="font-size: 0.75rem;">We: Rp {{ number_format($item->biaya_simaksi_weekend, 0, ',', '.') }}</span>
                            </td>
                            <td>{{ $item->estimasi_jam }} Jam</td>
                            <td>
                                @php
                                    $badgeColor = $item->tingkat_kesulitan == 'Sulit' ? 'bg-danger' : ($item->tingkat_kesulitan == 'Sedang' ? 'bg-warning text-dark' : 'bg-success');
                                @endphp
                                <span class="badge {{ $badgeColor }} px-3">{{ $item->tingkat_kesulitan }}</span>
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary" style="border-radius: 8px;" data-bs-toggle="modal" data-bs-target="#modalEditJalur{{ $item->id }}" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    
                                    <form action="{{ route('jalur.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus jalur?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: 8px;" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
 
                        {{-- MODAL EDIT JALUR --}}
                        <div class="modal fade" id="modalEditJalur{{ $item->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content modal-premium">
                                    <div class="modal-header bg-light">
                                        <h5 class="modal-title fw-bold text-dark d-flex align-items-center">
                                            <i class="bi bi-pencil-square text-info me-2" style="font-size: 1.25rem;"></i>Edit Jalur: {{ $item->nama_jalur }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('jalur.update', $item->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-body p-4 text-start">
                                            <div class="mb-3 text-start position-relative">
                                                <label class="form-label fw-bold small text-muted text-uppercase">Pilih Gunung</label>
                                                <input type="hidden" name="gunung_id" id="selected-edit-gunung-id-{{ $item->id }}" value="{{ $item->gunung_id }}" required>
                                                
                                                <button type="button" class="btn dropdown-btn-custom text-start d-flex justify-content-between align-items-center w-100 edit-gunung-dropdown-btn" id="edit-gunung-dropdown-btn-{{ $item->id }}" data-id="{{ $item->id }}">
                                                    <span id="edit-gunung-dropdown-label-{{ $item->id }}" class="text-dark fw-bold">
                                                        Gn. {{ str_ireplace('Gunung ', '', $item->gunung->nama_gunung) }}
                                                    </span>
                                                    <i class="bi bi-chevron-down text-secondary"></i>
                                                </button>
                                                
                                                <div class="custom-dropdown-menu d-none position-absolute bg-white shadow-lg border rounded-3 p-3 mt-1 edit-gunung-dropdown-menu" id="edit-gunung-dropdown-menu-{{ $item->id }}" style="z-index: 1050; left: 12px; right: 12px; max-height: 250px; overflow-y: auto;">
                                                    <input type="text" class="form-control mb-3 search-input" placeholder="Cari gunung...">
                                                    <div class="dropdown-list-items">
                                                        @foreach($gunungs as $g)
                                                            @php
                                                                $cleanName = str_ireplace('Gunung ', '', $g->nama_gunung);
                                                            @endphp
                                                            <div class="dropdown-item-card p-3 mb-2 rounded-3 {{ $item->gunung_id == $g->id ? 'selected' : '' }}" 
                                                                 data-id="{{ $g->id }}" 
                                                                 data-search="gn {{ strtolower($cleanName) }} {{ strtolower($g->lokasi) }}"
                                                                 data-gunung="Gn. {{ $cleanName }}"
                                                                 data-lokasi="{{ $g->lokasi }}">
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <div>
                                                                        <span class="fw-bold text-dark d-block" style="font-size: 0.95rem;">Gn. {{ $cleanName }}</span>
                                                                        <span class="text-muted small" style="font-size: 0.75rem;">Lokasi: {{ $g->lokasi }}</span>
                                                                    </div>
                                                                    <div>
                                                                        <span class="badge bg-light text-dark border" style="font-size: 0.7rem;">{{ number_format($g->ketinggian, 0, ',', '.') }} Mdpl</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-muted text-uppercase">Nama Jalur</label>
                                                <input type="text" name="nama_jalur" class="form-control form-control-premium" value="{{ $item->nama_jalur }}" required>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">Simaksi Weekday</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-white border-2 border-end-0" style="border-color: #e2e8f0; border-top-left-radius: 12px; border-bottom-left-radius: 12px; font-weight: 600; color: #64748b; padding: 12px 16px;">Rp</span>
                                                        <input type="text" name="biaya_simaksi_weekday" class="form-control price-format form-control-premium" value="{{ (int) $item->biaya_simaksi_weekday }}" required style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">Simaksi Weekend</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-white border-2 border-end-0" style="border-color: #e2e8f0; border-top-left-radius: 12px; border-bottom-left-radius: 12px; font-weight: 600; color: #64748b; padding: 12px 16px;">Rp</span>
                                                        <input type="text" name="biaya_simaksi_weekend" class="form-control price-format form-control-premium" value="{{ (int) $item->biaya_simaksi_weekend }}" required style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">Estimasi (Jam)</label>
                                                    <div class="input-group">
                                                        <input type="number" name="estimasi_jam" class="form-control form-control-premium" value="{{ (float) $item->estimasi_jam }}" step="any" required style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                                                        <span class="input-group-text bg-white border-2 border-start-0" style="border-color: #e2e8f0; border-top-right-radius: 12px; border-bottom-right-radius: 12px; font-weight: 600; color: #64748b; padding: 12px 16px;">Jam</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">Tingkat Kesulitan</label>
                                                    <select name="tingkat_kesulitan" class="form-select form-select-custom" required>
                                                        <option value="Mudah" {{ $item->tingkat_kesulitan == 'Mudah' ? 'selected' : '' }}>Mudah</option>
                                                        <option value="Sedang" {{ $item->tingkat_kesulitan == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                                                        <option value="Sulit" {{ $item->tingkat_kesulitan == 'Sulit' ? 'selected' : '' }}>Sulit</option>
                                                    </select>
                                                </div>
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
                        <tr><td colspan="7" class="text-center py-5 text-muted italic">Belum ada data jalur.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
 
{{-- MODAL TAMBAH JALUR --}}
<div class="modal fade" id="modalTambahJalur" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-premium">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center">
                    <i class="bi bi-plus-circle-fill text-primary me-2" style="font-size: 1.25rem;"></i>Tambah Jalur Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('jalur.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 text-start">
                    <div class="mb-3 text-start position-relative">
                        <label class="form-label fw-bold small text-muted text-uppercase">Pilih Gunung</label>
                        <input type="hidden" name="gunung_id" id="selected-add-gunung-id" required>
                        
                        <button type="button" class="btn dropdown-btn-custom text-start d-flex justify-content-between align-items-center w-100" id="add-gunung-dropdown-btn">
                            <span id="add-gunung-dropdown-label" class="text-muted small">-- Pilih Gunung --</span>
                            <i class="bi bi-chevron-down text-secondary"></i>
                        </button>
                        
                        <div class="custom-dropdown-menu d-none position-absolute bg-white shadow-lg border rounded-3 p-3 mt-1" id="add-gunung-dropdown-menu" style="z-index: 1050; left: 12px; right: 12px; max-height: 250px; overflow-y: auto;">
                            <input type="text" class="form-control mb-3 search-input" placeholder="Cari gunung...">
                            <div class="dropdown-list-items">
                                @foreach($gunungs as $g)
                                    @php
                                        $cleanName = str_ireplace('Gunung ', '', $g->nama_gunung);
                                    @endphp
                                    <div class="dropdown-item-card p-3 mb-2 rounded-3" 
                                         data-id="{{ $g->id }}" 
                                         data-search="gn {{ strtolower($cleanName) }} {{ strtolower($g->lokasi) }}"
                                         data-gunung="Gn. {{ $cleanName }}"
                                         data-lokasi="{{ $g->lokasi }}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="fw-bold text-dark d-block" style="font-size: 0.95rem;">Gn. {{ $cleanName }}</span>
                                                <span class="text-muted small" style="font-size: 0.75rem;">Lokasi: {{ $g->lokasi }}</span>
                                            </div>
                                            <div>
                                                <span class="badge bg-light text-dark border" style="font-size: 0.7rem;">{{ number_format($g->ketinggian, 0, ',', '.') }} Mdpl</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Nama Jalur</label>
                        <input type="text" name="nama_jalur" class="form-control form-control-premium" required placeholder="Masukkan nama jalur...">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Simaksi Weekday</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-2 border-end-0" style="border-color: #e2e8f0; border-top-left-radius: 12px; border-bottom-left-radius: 12px; font-weight: 600; color: #64748b; padding: 12px 16px;">Rp</span>
                                <input type="text" name="biaya_simaksi_weekday" class="form-control price-format form-control-premium" required placeholder="Contoh: 20.000" style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Simaksi Weekend</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-2 border-end-0" style="border-color: #e2e8f0; border-top-left-radius: 12px; border-bottom-left-radius: 12px; font-weight: 600; color: #64748b; padding: 12px 16px;">Rp</span>
                                <input type="text" name="biaya_simaksi_weekend" class="form-control price-format form-control-premium" required placeholder="Contoh: 25.000" style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Estimasi (Jam)</label>
                            <div class="input-group">
                                <input type="number" name="estimasi_jam" class="form-control form-control-premium" required placeholder="Contoh: 8" style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                                <span class="input-group-text bg-white border-2 border-start-0" style="border-color: #e2e8f0; border-top-right-radius: 12px; border-bottom-right-radius: 12px; font-weight: 600; color: #64748b; padding: 12px 16px;">Jam</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Tingkat Kesulitan</label>
                            <select name="tingkat_kesulitan" class="form-select form-select-custom" required>
                                <option value="Mudah">Mudah</option>
                                <option value="Sedang" selected>Sedang</option>
                                <option value="Sulit">Sulit</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-premium-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-premium-primary fw-bold">Simpan Jalur</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
        function formatPriceString(value) {
            let raw = value.replace(/\D/g, '');
            return raw.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Bind input events to format numbers dynamically
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('price-format')) {
                let selectionStart = e.target.selectionStart;
                let originalLength = e.target.value.length;
                
                let formatted = formatPriceString(e.target.value);
                e.target.value = formatted;
                
                let newLength = formatted.length;
                let diff = newLength - originalLength;
                e.target.setSelectionRange(selectionStart + diff, selectionStart + diff);
            }
        });

        // Format pre-existing values in edit forms on load
        document.querySelectorAll('.price-format').forEach(function(input) {
            if (input.value) {
                input.value = formatPriceString(input.value);
            }
        });

        // Strip formatting dots before submitting form to backend
        document.addEventListener('submit', function(e) {
            e.target.querySelectorAll('.price-format').forEach(function(input) {
                if (input.value.trim() !== '') {
                    input.value = input.value.replace(/\D/g, '');
                }
            });
        });

        // Searchable Dropdown for Add Jalur Modal
        function initAddGunungDropdown() {
            const btn = document.getElementById('add-gunung-dropdown-btn');
            const label = document.getElementById('add-gunung-dropdown-label');
            const menu = document.getElementById('add-gunung-dropdown-menu');
            const hiddenInput = document.getElementById('selected-add-gunung-id');
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
                    
                    const gunung = item.getAttribute('data-gunung');
                    label.innerHTML = `<strong>${gunung}</strong>`;
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
        initAddGunungDropdown();

        // Searchable Dropdowns for Edit Jalur Modals
        function initEditGunungDropdowns() {
            document.querySelectorAll('.edit-gunung-dropdown-btn').forEach(function(btn) {
                const id = btn.getAttribute('data-id');
                const label = document.getElementById('edit-gunung-dropdown-label-' + id);
                const menu = document.getElementById('edit-gunung-dropdown-menu-' + id);
                const hiddenInput = document.getElementById('selected-edit-gunung-id-' + id);
                if (!btn || !menu) return;
                const searchInput = menu.querySelector('.search-input');
                const items = menu.querySelectorAll('.dropdown-item-card');

                // Toggle menu
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    // Close other menus first
                    document.querySelectorAll('.edit-gunung-dropdown-menu').forEach(function(otherMenu) {
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
                        
                        const gunung = item.getAttribute('data-gunung');
                        label.innerHTML = `<strong>${gunung}</strong>`;
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
        initEditGunungDropdowns();

        // Validation for Add form submit
        const addForm = document.querySelector('#modalTambahJalur form');
        if (addForm) {
            addForm.addEventListener('submit', function(e) {
                const selectedGunung = document.getElementById('selected-add-gunung-id').value;
                if (!selectedGunung) {
                    alert('Silakan pilih Gunung terlebih dahulu!');
                    e.preventDefault();
                    return false;
                }
            });
        }
</script>
@endsection