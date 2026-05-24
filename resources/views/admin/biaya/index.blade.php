@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark">Master Data Biaya Transportasi</h3>
            <p class="text-muted small">Kelola armada bus, rute terminal, dan harga periode khusus.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-warning fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalPeriodPrice">
                <i class="bi bi-calendar-check me-2"></i> Set Harga Periode
            </button>
            <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahBiaya">
                <i class="bi bi-plus-lg me-2"></i> Tambah Jalur Bus
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Jalur Gunung</th>
                            <th>Armada</th>
                            <th>Rute (Start → End)</th>
                            <th>Estimasi</th>
                            <th>Tarif Normal</th>
                            <th>Harga Periode</th>
                            <th class="text-center pe-4">Aksi</th>
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
                                <span class="badge border border-primary text-primary">{{ $item->start_terminal->nama_terminal }}</span>
                                <i class="bi bi-arrow-right mx-1"></i>
                                <span class="badge border border-success text-success">{{ $item->end_terminal->nama_terminal }}</span>
                            </td>
                            <td>{{ $item->estimasi_perjalanan }} Jam</td>
                            <td>
                                <span class="d-block"><small class="text-muted">Wd:</small> <strong>Rp {{ number_format($item->harga_pp, 0, ',', '.') }}</strong></span>
                                <span class="d-block text-success"><small class="text-muted">We:</small> <strong>Rp {{ number_format($item->harga_weekend ?? $item->harga_pp, 0, ',', '.') }}</strong></span>
                            </td>
                            <td>
                                @if($item->start_date)
                                    <div class="p-2 border rounded bg-light d-flex align-items-center justify-content-between">
                                        <div>
                                            <span class="text-danger fw-bold d-block">Rp {{ number_format($item->harga_periode, 0, ',', '.') }}</span>
                                            <small class="text-muted" style="font-size: 0.75rem;">{{ $item->start_date }} s/d {{ $item->end_date }}</small>
                                        </div>
                                        {{-- TOMBOL RESET PERIODE KHUSUS --}}
                                        <form action="{{ route('biaya.reset_period', $item->id) }}" method="POST" class="ms-2" onsubmit="return confirm('Apakah Anda yakin ingin mereset harga periode armada ini kembali ke normal?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-warning text-dark border-0 p-1 px-2" title="Reset Periode Jadi Normal">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#modalEditBiaya{{ $item->id }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    
                                    <form action="{{ route('biaya.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger border-0">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- MODAL EDIT BIAYA --}}
                        <div class="modal fade" id="modalEditBiaya{{ $item->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header bg-info text-white">
                                        <h5 class="modal-title fw-bold">Edit Jalur Bus</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('biaya.update', $item->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-body p-4 text-start">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">UNTUK JALUR PENDAKIAN</label>
                                                <select name="jalur_id" class="form-select" required>
                                                    @foreach($jalurs as $j)
                                                        <option value="{{ $j->id }}" {{ $item->jalur_id == $j->id ? 'selected' : '' }}>
                                                            {{ $j->gunung->nama_gunung }} - {{ $j->nama_jalur }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">NAMA ARMADA</label>
                                                <input type="text" name="nama_armada" class="form-control" value="{{ $item->nama_armada }}" required>
                                            </div>
                                            <div class="row">
                                                <div class="col-6 mb-3">
                                                    <label class="form-label fw-bold small">START POINT</label>
                                                    <select name="start_terminal_id" class="form-select" required>
                                                        @foreach($startPoints as $s)
                                                            <option value="{{ $s->id }}" {{ $item->start_terminal_id == $s->id ? 'selected' : '' }}>{{ $s->nama_terminal }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <label class="form-label fw-bold small">END POINT</label>
                                                    <select name="end_terminal_id" class="form-select" required>
                                                        @foreach($endPoints as $e)
                                                            <option value="{{ $e->id }}" {{ $item->end_terminal_id == $e->id ? 'selected' : '' }}>{{ $e->nama_terminal }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 mb-3">
                                                    <label class="form-label fw-bold small">ESTIMASI (JAM)</label>
                                                    <input type="number" name="estimasi_perjalanan" class="form-control" value="{{ $item->estimasi_perjalanan }}" required>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6 mb-3">
                                                    <label class="form-label fw-bold small">HARGA PP (WEEKDAY)</label>
                                                    <input type="number" name="harga_pp" class="form-control" value="{{ $item->harga_pp }}" required>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <label class="form-label fw-bold small text-success">HARGA WEEKEND</label>
                                                    <input type="number" name="harga_weekend" class="form-control border-success" value="{{ $item->harga_weekend }}" placeholder="Kosongkan jika sama">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light">
                                            <button type="submit" class="btn btn-info text-white px-4 fw-bold">Update Data</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr><td colspan="8" class="text-center py-5">Belum ada data.</td></tr>
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
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">Tambah Jalur Bus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('biaya.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">UNTUK JALUR PENDAKIAN</label>
                        <select name="jalur_id" class="form-select" required>
                            <option value="" disabled selected>Pilih Jalur Gunung</option>
                            @foreach($jalurs as $j)
                                <option value="{{ $j->id }}">{{ $j->gunung->nama_gunung }} - {{ $j->nama_jalur }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">NAMA ARMADA</label>
                        <input type="text" name="nama_armada" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold small">START POINT</label>
                            <select name="start_terminal_id" class="form-select" required>
                                <option value="" disabled selected>Pilih</option>
                                @foreach($startPoints as $s) <option value="{{ $s->id }}">{{ $s->nama_terminal }}</option> @endforeach
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold small">END POINT</label>
                            <select name="end_terminal_id" class="form-select" required>
                                <option value="" disabled selected>Pilih</option>
                                @foreach($endPoints as $e) <option value="{{ $e->id }}">{{ $e->nama_terminal }}</option> @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold small">ESTIMASI (JAM)</label>
                            <input type="number" name="estimasi_perjalanan" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold small">HARGA PP (WEEKDAY)</label>
                            <input type="number" name="harga_pp" class="form-control" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold small text-success">HARGA WEEKEND</label>
                            <input type="number" name="harga_weekend" class="form-control border-success" placeholder="Kosongkan jika sama">
                        </div>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary px-4 fw-bold">Simpan</button></div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL SET HARGA PERIODE --}}
<div class="modal fade" id="modalPeriodPrice" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning">
                <h5 class="modal-title fw-bold">Set Harga Periode</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('biaya.apply_period') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">PILIH ARMADA</label>
                        <select name="biaya_id" class="form-select" required>
                            @foreach($biayas as $b)
                                <option value="{{ $b->id }}">{{ $b->nama_armada }} ({{ $b->start_terminal->nama_terminal }} - {{ $b->end_terminal->nama_terminal }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold small text-uppercase">Dari Tanggal</label>
                            <input type="date" name="start_date" class="form-control" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold small text-uppercase">Sampai Tanggal</label>
                            <input type="date" name="end_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold small">HARGA PERIODE (PP)</label>
                        <input type="number" name="harga_periode" class="form-control border-warning" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning w-100 fw-bold">APPLY PRICE</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection