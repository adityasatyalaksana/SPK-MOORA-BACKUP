@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark">Master Data Terminal</h3>
            <p class="text-muted small">Kelola titik keberangkatan (Starting) dan penjemputan (Ending).</p>
        </div>
        <button type="button" class="btn btn-primary btn-premium-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahTerminal">
            <i class="bi bi-plus-lg me-2"></i> Tambah Terminal
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card premium-card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle premium-table">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th class="ps-4" width="50">No</th>
                            <th>Nama Terminal</th>
                            <th>Lokasi</th>
                            <th>Tipe</th>
                            <th class="text-center" width="200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($terminals as $key => $item)
                        <tr>
                            <td class="ps-4">{{ $key + 1 }}</td>
                            <td class="fw-bold">{{ $item->nama_terminal }}</td>
                            <td>{{ $item->lokasi }}</td>
                            <td>
                                @if($item->tipe == 'Starting Point')
                                    <span class="badge bg-primary px-3">Starting Point</span>
                                @else
                                    <span class="badge bg-info text-dark px-3">Ending Point</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary" style="border-radius: 8px;" data-bs-toggle="modal" data-bs-target="#modalEditTerminal{{ $item->id }}" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
 
                                    <form action="{{ route('terminal.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus terminal {{ $item->nama_terminal }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: 8px;" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
 
                        <div class="modal fade" id="modalEditTerminal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content modal-premium">
                                    <div class="modal-header bg-light">
                                        <h5 class="modal-title fw-bold text-dark d-flex align-items-center">
                                            <i class="bi bi-pencil-square text-info me-2" style="font-size: 1.25rem;"></i>Edit Terminal
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('terminal.update', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body p-4 text-start">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-muted text-uppercase">Nama Terminal</label>
                                                <input type="text" name="nama_terminal" class="form-control form-control-premium" value="{{ $item->nama_terminal }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-muted text-uppercase">Lokasi (Kota/Kabupaten)</label>
                                                <input type="text" name="lokasi" class="form-control form-control-premium" value="{{ $item->lokasi }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-muted text-uppercase">Tipe Terminal</label>
                                                <select name="tipe" class="form-select form-select-custom" required>
                                                    <option value="Starting Point" {{ $item->tipe == 'Starting Point' ? 'selected' : '' }}>Starting Point</option>
                                                    <option value="Ending Point" {{ $item->tipe == 'Ending Point' ? 'selected' : '' }}>Ending Point</option>
                                                </select>
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
                            <td colspan="5" class="text-center py-5 text-muted">Belum ada data terminal.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
 
<div class="modal fade" id="modalTambahTerminal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-premium">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center">
                    <i class="bi bi-plus-circle-fill text-primary me-2" style="font-size: 1.25rem;"></i>Tambah Terminal Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('terminal.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 text-start">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Nama Terminal</label>
                        <input type="text" name="nama_terminal" class="form-control form-control-premium" required placeholder="Contoh: Terminal Arjosari">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Lokasi (Kota/Kabupaten)</label>
                        <input type="text" name="lokasi" class="form-control form-control-premium" required placeholder="Contoh: Malang">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Tipe Terminal</label>
                        <select name="tipe" class="form-select form-select-custom" required>
                            <option value="" selected disabled>-- Pilih Tipe --</option>
                            <option value="Starting Point">Starting Point</option>
                            <option value="Ending Point">Ending Point</option>
                        </select>
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
@endsection