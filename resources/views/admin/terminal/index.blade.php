@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark">Master Data Terminal</h3>
            <p class="text-muted small">Kelola titik keberangkatan (Starting) dan penjemputan (Ending).</p>
        </div>
        <button type="button" class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahTerminal">
            <i class="bi bi-plus-lg me-2"></i> Tambah Terminal
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="50">No</th>
                            <th>Nama Terminal</th>
                            <th>Lokasi</th>
                            <th>Tipe</th>
                            <th class="text-center" width="200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($terminals as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td class="fw-bold">{{ $item->nama_terminal }}</td>
                            <td>{{ $item->lokasi }}</td>
                            <td>
                                @if($item->tipe == 'Starting Point')
                                    <span class="badge bg-primary px-3">Starting Point</span>
                                @else
                                    <span class="badge bg-info text-dark px-3">Ending Point</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEditTerminal{{ $item->id }}" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <form action="{{ route('terminal.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus terminal {{ $item->nama_terminal }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalEditTerminal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold">Edit Terminal</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('terminal.update', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body text-start">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">NAMA TERMINAL</label>
                                                <input type="text" name="nama_terminal" class="form-control" value="{{ $item->nama_terminal }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">LOKASI (KOTA/KABUPATEN)</label>
                                                <input type="text" name="lokasi" class="form-control" value="{{ $item->lokasi }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">TIPE TERMINAL</label>
                                                <select name="tipe" class="form-select" required>
                                                    <option value="Starting Point" {{ $item->tipe == 'Starting Point' ? 'selected' : '' }}>Starting Point</option>
                                                    <option value="Ending Point" {{ $item->tipe == 'Ending Point' ? 'selected' : '' }}>Ending Point</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
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
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Terminal Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('terminal.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase">Nama Terminal</label>
                        <input type="text" name="nama_terminal" class="form-control" required placeholder="Contoh: Terminal Arjosari">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase">Lokasi</label>
                        <input type="text" name="lokasi" class="form-control" required placeholder="Contoh: Malang">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase">Tipe Terminal</label>
                        <select name="tipe" class="form-select" required>
                            <option value="" selected disabled>-- Pilih Tipe --</option>
                            <option value="Starting Point">Starting Point</option>
                            <option value="Ending Point">Ending Point</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success px-4">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection