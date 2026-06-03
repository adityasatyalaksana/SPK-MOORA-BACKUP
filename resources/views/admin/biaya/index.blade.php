@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark">Master Data Biaya Transportasi</h3>
            <p class="text-muted small">Kelola armada bus, rute terminal, dan harga periode khusus.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-warning btn-premium-primary text-dark shadow-sm" data-bs-toggle="modal" data-bs-target="#modalPeriodPrice">
                <i class="bi bi-calendar-check me-2"></i> Set Harga Periode
            </button>
            <button class="btn btn-primary btn-premium-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahBiaya">
                <i class="bi bi-plus-lg me-2"></i> Tambah Jalur Bus
            </button>
        </div>
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
                            <th class="ps-4" width="50">No</th>
                            <th>Jalur Gunung</th>
                            <th>Armada</th>
                            <th>Rute (Start → End)</th>
                            <th>Estimasi</th>
                            <th>Tarif Normal</th>
                            <th>Harga Periode</th>
                            <th class="text-center" width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($biayas as $key => $item)
                        <tr>
                            <td class="ps-4">{{ $key + 1 }}</td>
                            <td>
                                <span class="fw-bold text-primary">{{ $item->jalur->gunung->nama_gunung ?? '-' }}</span><br>
                                <small class="text-muted">Via: {{ $item->jalur->nama_jalur ?? '-' }}</small>
                            </td>
                            <td><span class="fw-bold text-dark">{{ $item->nama_armada }}</span></td>
                            <td>
                                <span class="badge border border-primary text-primary" style="border-radius: 6px; padding: 4px 8px;">{{ $item->start_terminal->nama_terminal }}</span>
                                <i class="bi bi-arrow-right mx-1"></i>
                                <span class="badge border border-success text-success" style="border-radius: 6px; padding: 4px 8px;">{{ $item->end_terminal->nama_terminal }}</span>
                            </td>
                            <td>{{ $item->estimasi_perjalanan }} Jam</td>
                            <td>
                                <span class="d-block"><small class="text-muted">Wd:</small> <strong>Rp {{ number_format($item->harga_pp, 0, ',', '.') }}</strong></span>
                                <span class="d-block text-success"><small class="text-muted">We:</small> <strong>Rp {{ number_format($item->harga_weekend ?? $item->harga_pp, 0, ',', '.') }}</strong></span>
                            </td>
                            <td>
                                @if($item->start_date)
                                    <div class="p-2 border d-flex align-items-center justify-content-between shadow-sm" style="background-color: #fff5f5; border-color: #ffe3e3 !important; border-radius: 12px; min-width: 210px;">
                                        <div>
                                            <span class="text-danger fw-bold d-block mb-1" style="font-size: 0.9rem;">
                                                <i class="bi bi-tag-fill me-1"></i>Rp {{ number_format($item->harga_periode, 0, ',', '.') }}
                                            </span>
                                            <small class="text-muted d-block" style="font-size: 0.72rem;">
                                                <i class="bi bi-calendar3 text-danger me-1"></i>{{ \Carbon\Carbon::parse($item->start_date)->translatedFormat('d M') }} - {{ \Carbon\Carbon::parse($item->end_date)->translatedFormat('d M Y') }}
                                            </small>
                                        </div>
                                        {{-- TOMBOL RESET PERIODE KHUSUS --}}
                                        <form action="{{ route('biaya.reset_period', $item->id) }}" method="POST" class="ms-2" onsubmit="return confirm('Apakah Anda yakin ingin mereset harga periode armada ini kembali ke normal?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-light p-1 px-2 border shadow-sm d-flex align-items-center justify-content-center" style="border-radius: 8px; background-color: #ffffff;" title="Reset Periode Jadi Normal">
                                                <i class="bi bi-arrow-counterclockwise text-danger fw-bold" style="font-size: 0.85rem;"></i>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary px-2.5 py-1.5 border border-secondary-subtle" style="font-weight: 500; border-radius: 8px;">Normal</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary" style="border-radius: 8px;" data-bs-toggle="modal" data-bs-target="#modalEditBiaya{{ $item->id }}" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    
                                    <form action="{{ route('biaya.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: 8px;" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- MODAL EDIT BIAYA --}}
                        <div class="modal fade" id="modalEditBiaya{{ $item->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content modal-premium">
                                    <div class="modal-header bg-light">
                                        <h5 class="modal-title fw-bold text-dark d-flex align-items-center">
                                            <i class="bi bi-pencil-square text-info me-2" style="font-size: 1.25rem;"></i>Edit Jalur Bus
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('biaya.update', $item->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-body p-4 text-start">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-muted text-uppercase">Untuk Jalur Pendakian</label>
                                                <select name="jalur_id" class="form-select form-select-custom" required>
                                                    @foreach($jalurs as $j)
                                                        <option value="{{ $j->id }}" {{ $item->jalur_id == $j->id ? 'selected' : '' }}>
                                                            {{ $j->gunung->nama_gunung }} - {{ $j->nama_jalur }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-muted text-uppercase">Nama Armada</label>
                                                <input type="text" name="nama_armada" class="form-control form-control-premium" value="{{ $item->nama_armada }}" required>
                                            </div>
                                            <div class="row">
                                                <div class="col-6 mb-3">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">Start Point</label>
                                                    <select name="start_terminal_id" class="form-select form-select-custom" required>
                                                        @foreach($startPoints as $s)
                                                            <option value="{{ $s->id }}" {{ $item->start_terminal_id == $s->id ? 'selected' : '' }}>{{ $s->nama_terminal }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">End Point</label>
                                                    <select name="end_terminal_id" class="form-select form-select-custom" required>
                                                        @foreach($endPoints as $e)
                                                            <option value="{{ $e->id }}" {{ $item->end_terminal_id == $e->id ? 'selected' : '' }}>{{ $e->nama_terminal }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 mb-3">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">Estimasi (Jam)</label>
                                                    <input type="number" name="estimasi_perjalanan" class="form-control form-control-premium" value="{{ $item->estimasi_perjalanan }}" required>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6 mb-3">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">Harga PP (Weekday)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-white border-2 border-end-0" style="border-color: #e2e8f0; border-top-left-radius: 12px; border-bottom-left-radius: 12px; font-weight: 600; color: #64748b; padding: 12px 16px;">Rp</span>
                                                        <input type="text" name="harga_pp" class="form-control form-control-premium price-format" value="{{ $item->harga_pp }}" required style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                                    </div>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <label class="form-label fw-bold small text-success text-uppercase">Harga Weekend</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-white border-2 border-end-0 border-success" style="border-top-left-radius: 12px; border-bottom-left-radius: 12px; font-weight: 600; color: #157347; padding: 12px 16px;">Rp</span>
                                                        <input type="text" name="harga_weekend" class="form-control form-control-premium border-success price-format" value="{{ $item->harga_weekend }}" placeholder="Kosongkan jika sama" style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                                    </div>
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
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-folder2-open d-block mb-2" style="font-size: 2rem;"></i>
                                Belum ada data biaya transportasi.
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
<div class="modal fade" id="modalTambahBiaya" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-premium">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center">
                    <i class="bi bi-plus-circle-fill text-primary me-2" style="font-size: 1.25rem;"></i>Tambah Jalur Bus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('biaya.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 text-start">
                    <div class="mb-3 text-start position-relative">
                        <label class="form-label fw-bold small text-muted text-uppercase">Untuk Jalur Pendakian</label>
                        <input type="hidden" name="jalur_id" id="selected-add-jalur-id" required>
                        
                        <button type="button" class="btn dropdown-btn-custom text-start d-flex justify-content-between align-items-center w-100" id="add-jalur-dropdown-btn">
                            <span id="add-jalur-dropdown-label" class="text-muted small">-- Pilih Rute Gunung --</span>
                            <i class="bi bi-chevron-down text-secondary"></i>
                        </button>
                        
                        <div class="custom-dropdown-menu d-none position-absolute bg-white shadow-lg border rounded-3 p-3 mt-1" id="add-jalur-dropdown-menu" style="z-index: 1050; left: 12px; right: 12px; max-height: 250px; overflow-y: auto;">
                            <input type="text" class="form-control mb-3 search-input" placeholder="Cari gunung atau jalur...">
                            <div class="dropdown-list-items">
                                @foreach($jalurs as $j)
                                    @php
                                        $cleanName = str_ireplace('Gunung ', '', $j->gunung->nama_gunung);
                                    @endphp
                                    <div class="dropdown-item-card p-3 mb-2 rounded-3" 
                                         data-id="{{ $j->id }}" 
                                         data-search="gn {{ strtolower($cleanName) }} {{ strtolower($j->nama_jalur) }}"
                                         data-gunung="Gn. {{ $cleanName }}"
                                         data-jalur="Jalur {{ $j->nama_jalur }}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="fw-bold text-dark d-block" style="font-size: 0.95rem;">Gn. {{ $cleanName }}</span>
                                                <span class="text-muted small" style="font-size: 0.75rem;">Jalur: {{ $j->nama_jalur }}</span>
                                            </div>
                                            <div>
                                                <span class="badge bg-light text-dark border" style="font-size: 0.7rem;">{{ number_format($j->gunung->ketinggian, 0, ',', '.') }} Mdpl</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Nama Armada</label>
                        <input type="text" name="nama_armada" class="form-control form-control-premium" required placeholder="Contoh: PO Primajasa">
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Start Point</label>
                            <select name="start_terminal_id" class="form-select form-select-custom" required>
                                <option value="" disabled selected>Pilih</option>
                                @foreach($startPoints as $s) <option value="{{ $s->id }}">{{ $s->nama_terminal }}</option> @endforeach
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">End Point</label>
                            <select name="end_terminal_id" class="form-select form-select-custom" required>
                                <option value="" disabled selected>Pilih</option>
                                @foreach($endPoints as $e) <option value="{{ $e->id }}">{{ $e->nama_terminal }}</option> @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Estimasi (Jam)</label>
                            <input type="number" name="estimasi_perjalanan" class="form-control form-control-premium" required placeholder="Estimasi jam perjalanan...">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Harga PP (Weekday)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-2 border-end-0" style="border-color: #e2e8f0; border-top-left-radius: 12px; border-bottom-left-radius: 12px; font-weight: 600; color: #64748b; padding: 12px 16px;">Rp</span>
                                <input type="text" name="harga_pp" class="form-control form-control-premium price-format" required placeholder="Harga weekday..." style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold small text-success text-uppercase">Harga Weekend</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-2 border-end-0 border-success" style="border-top-left-radius: 12px; border-bottom-left-radius: 12px; font-weight: 600; color: #157347; padding: 12px 16px;">Rp</span>
                                <input type="text" name="harga_weekend" class="form-control form-control-premium border-success price-format" placeholder="Kosongkan jika sama" style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                            </div>
                        </div>
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

{{-- MODAL SET HARGA PERIODE --}}
<div class="modal fade" id="modalPeriodPrice" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-premium">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center">
                    <i class="bi bi-calendar-check text-warning me-2" style="font-size: 1.25rem;"></i>Set Harga Periode
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('biaya.apply_period') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3 text-start position-relative">
                        <label class="form-label fw-bold small text-muted text-uppercase">Pilih Armada</label>
                        <input type="hidden" name="biaya_id" id="selected-period-biaya-id" required>
                        
                        <button type="button" class="btn dropdown-btn-custom text-start d-flex justify-content-between align-items-center w-100" id="period-biaya-dropdown-btn">
                            <span id="period-biaya-dropdown-label" class="text-muted small">-- Pilih Armada --</span>
                            <i class="bi bi-chevron-down text-secondary"></i>
                        </button>
                        
                        <div class="custom-dropdown-menu d-none position-absolute bg-white shadow-lg border rounded-3 p-3 mt-1" id="period-biaya-dropdown-menu" style="z-index: 1050; left: 12px; right: 12px; max-height: 250px; overflow-y: auto;">
                            <input type="text" class="form-control mb-3 search-input" placeholder="Cari armada atau terminal...">
                            <div class="dropdown-list-items">
                                @foreach($biayas as $b)
                                    @php
                                        $cleanStart = str_ireplace('Terminal ', '', $b->start_terminal->nama_terminal);
                                        $cleanEnd = str_ireplace('Terminal ', '', $b->end_terminal->nama_terminal);
                                    @endphp
                                    <div class="dropdown-item-card p-3 mb-2 rounded-3" 
                                         data-id="{{ $b->id }}" 
                                         data-search="{{ strtolower($b->nama_armada) }} {{ strtolower($cleanStart) }} {{ strtolower($cleanEnd) }}"
                                         data-armada="{{ $b->nama_armada }}"
                                         data-route="({{ $cleanStart }} - {{ $cleanEnd }})">
                                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                            <div>
                                                <span class="fw-bold text-dark d-block" style="font-size: 0.95rem;">{{ $b->nama_armada }}</span>
                                                <span class="text-muted small" style="font-size: 0.75rem;">
                                                    {{ $cleanStart }} &rarr; {{ $cleanEnd }}
                                                </span>
                                            </div>
                                            <div>
                                                <span class="badge bg-success text-white fw-bold" style="font-size: 0.7rem;">Rp {{ number_format($b->harga_pp, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3 text-start">
                            <label class="form-label fw-bold small text-muted text-uppercase">Dari Tanggal</label>
                            <input type="date" name="start_date" class="form-control form-control-premium" required>
                        </div>
                        <div class="col-6 mb-3 text-start">
                            <label class="form-label fw-bold small text-muted text-uppercase">Sampai Tanggal</label>
                            <input type="date" name="end_date" class="form-control form-control-premium" required>
                        </div>
                    </div>
                    <div class="mb-0 text-start">
                        <label class="form-label fw-bold small text-muted text-uppercase">Harga Periode (PP)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-2 border-end-0" style="border-color: #e2e8f0; border-top-left-radius: 12px; border-bottom-left-radius: 12px; font-weight: 600; color: #64748b; padding: 12px 16px;">Rp</span>
                            <input type="text" name="harga_periode" class="form-control form-control-premium price-format" required placeholder="Contoh: 150.000" style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-premium-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning btn-premium-primary text-dark fw-bold">Apply Price</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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
                // If it is empty (e.g. optional weekend price), keep it empty
                if (input.value.trim() !== '') {
                    input.value = input.value.replace(/\D/g, '');
                }
            });
        });

        // Searchable Dropdown for Period Price Modal
        function initPeriodBiayaDropdown() {
            const btn = document.getElementById('period-biaya-dropdown-btn');
            const label = document.getElementById('period-biaya-dropdown-label');
            const menu = document.getElementById('period-biaya-dropdown-menu');
            const hiddenInput = document.getElementById('selected-period-biaya-id');
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
                    
                    const armada = item.getAttribute('data-armada');
                    const route = item.getAttribute('data-route');
                    label.innerHTML = `<strong>${armada}</strong> <span class="text-secondary small ms-1">${route}</span>`;
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
        initPeriodBiayaDropdown();

        // Searchable Dropdown for Add Bus Route Modal (Pilih Jalur Gunung)
        function initAddJalurDropdown() {
            const btn = document.getElementById('add-jalur-dropdown-btn');
            const label = document.getElementById('add-jalur-dropdown-label');
            const menu = document.getElementById('add-jalur-dropdown-menu');
            const hiddenInput = document.getElementById('selected-add-jalur-id');
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
                    const jalur = item.getAttribute('data-jalur');
                    label.innerHTML = `<strong>${gunung}</strong> <span class="text-secondary small ms-1">(${jalur})</span>`;
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
        initAddJalurDropdown();

        // Validation for Period form submit
        const periodForm = document.querySelector('#modalPeriodPrice form');
        if (periodForm) {
            periodForm.addEventListener('submit', function(e) {
                const selectedBiaya = document.getElementById('selected-period-biaya-id').value;
                if (!selectedBiaya) {
                    alert('Silakan pilih Armada terlebih dahulu!');
                    e.preventDefault();
                    return false;
                }
            });
        }

        // Validation for Add form submit
        const addForm = document.querySelector('#modalTambahBiaya form');
        if (addForm) {
            addForm.addEventListener('submit', function(e) {
                const selectedJalur = document.getElementById('selected-add-jalur-id').value;
                if (!selectedJalur) {
                    alert('Silakan pilih Rute Gunung terlebih dahulu!');
                    e.preventDefault();
                    return false;
                }
            });
        }
    });
</script>
@endsection