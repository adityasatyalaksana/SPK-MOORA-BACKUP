@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark">Master Data Kriteria</h3>
            <p class="text-muted small">Tentukan bobot dan tipe kriteria untuk perhitungan metode MOORA.</p>
        </div>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahKriteria">
            <i class="bi bi-plus-lg me-2"></i> Tambah Kriteria
        </button>
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
                            <th class="ps-4" width="50">No</th>
                            <th>Kode</th>
                            <th>Nama Kriteria</th>
                            <th>Tipe</th>
                            <th>Bobot</th>
                            <th class="text-center" width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kriterias as $key => $item)
                        <tr>
                            <td class="ps-4">{{ $key + 1 }}</td>
                            <td><span class="badge bg-dark">{{ $item->kode_kriteria }}</span></td>
                            <td class="fw-bold">{{ $item->nama_kriteria }}</td>
                            <td>
                                @if($item->tipe == 'Benefit')
                                    <span class="text-success fw-bold"><i class="bi bi-arrow-up-circle me-1"></i> Benefit</span>
                                @else
                                    <span class="text-danger fw-bold"><i class="bi bi-arrow-down-circle me-1"></i> Cost</span>
                                @endif
                            </td>
                            <td>{{ $item->bobot }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    {{-- Tombol Edit --}}
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEditKriteria{{ $item->id }}" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
 
                                     {{-- Tombol Hapus --}}
                                     <form action="{{ route('kriteria.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus kriteria ini?')">
                                         @csrf @method('DELETE')
                                         <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                             <i class="bi bi-trash"></i>
                                         </button>
                                     </form>
                                 </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalEditKriteria{{ $item->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header bg-info text-white">
                                        <h5 class="modal-title fw-bold">Edit Kriteria</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('kriteria.update', $item->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-body p-4">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">KODE KRITERIA</label>
                                                <input type="text" name="kode_kriteria" class="form-control" value="{{ $item->kode_kriteria }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">NAMA KRITERIA</label>
                                                <input type="text" name="nama_kriteria" class="form-control" value="{{ $item->nama_kriteria }}" required>
                                            </div>
                                            <div class="row">
                                                <div class="col-6 mb-3">
                                                    <label class="form-label fw-bold small">TIPE</label>
                                                    <select name="tipe" class="form-select" required>
                                                        <option value="Benefit" {{ $item->tipe == 'Benefit' ? 'selected' : '' }}>Benefit</option>
                                                        <option value="Cost" {{ $item->tipe == 'Cost' ? 'selected' : '' }}>Cost</option>
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <label class="form-label fw-bold small">BOBOT</label>
                                                    <input type="number" step="0.01" name="bobot" class="form-control" value="{{ $item->bobot }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light">
                                            <button type="submit" class="btn btn-info text-white px-4 fw-bold">Update Kriteria</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr><td colspan="6" class="text-center py-5">Belum ada data kriteria.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahKriteria" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">Tambah Kriteria Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('kriteria.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">KODE KRITERIA</label>
                        <input type="text" name="kode_kriteria" class="form-control" placeholder="Contoh: C1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">NAMA KRITERIA</label>
                        <input type="text" name="nama_kriteria" class="form-control" placeholder="Contoh: Harga Tiket" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold small">TIPE</label>
                            <select name="tipe" class="form-select" required>
                                <option value="Benefit">Benefit</option>
                                <option value="Cost">Cost</option>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold small">BOBOT</label>
                            <input type="number" step="0.01" name="bobot" class="form-control" placeholder="Contoh: 0.25" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="submit" class="btn btn-primary px-4 fw-bold">Simpan Kriteria</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection