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

    <div class="card premium-card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle premium-table">
                    <thead class="bg-dark text-white">
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
                            <td><span class="badge bg-dark px-2.5 py-1.5" style="border-radius: 6px;">{{ $item->kode_kriteria }}</span></td>
                            <td class="fw-bold">{{ $item->nama_kriteria }}</td>
                            <td>
                                @if($item->tipe == 'Benefit')
                                    <span class="text-success fw-bold"><i class="bi bi-arrow-up-circle-fill me-1"></i> Benefit</span>
                                @else
                                    <span class="text-danger fw-bold"><i class="bi bi-arrow-down-circle-fill me-1"></i> Cost</span>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $item->bobot }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    {{-- Tombol Edit --}}
                                    <button type="button" class="btn btn-sm btn-outline-primary" style="border-radius: 8px;" data-bs-toggle="modal" data-bs-target="#modalEditKriteria{{ $item->id }}" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
 
                                     {{-- Tombol Hapus --}}
                                     <form action="{{ route('kriteria.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus kriteria ini?')">
                                         @csrf @method('DELETE')
                                         <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: 8px;" title="Hapus">
                                             <i class="bi bi-trash"></i>
                                         </button>
                                     </form>
                                 </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalEditKriteria{{ $item->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content modal-premium">
                                    <div class="modal-header bg-light">
                                        <h5 class="modal-title fw-bold text-dark d-flex align-items-center">
                                            <i class="bi bi-pencil-square text-info me-2" style="font-size: 1.25rem;"></i>Edit Kriteria
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('kriteria.update', $item->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-body p-4 text-start">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-muted text-uppercase">Kode Kriteria</label>
                                                <input type="text" name="kode_kriteria" class="form-control form-control-premium" value="{{ $item->kode_kriteria }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-muted text-uppercase">Nama Kriteria</label>
                                                <input type="text" name="nama_kriteria" class="form-control form-control-premium" value="{{ $item->nama_kriteria }}" required>
                                            </div>
                                            <div class="row">
                                                <div class="col-6 mb-3">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">Tipe</label>
                                                    <select name="tipe" class="form-select form-select-custom" required>
                                                        <option value="Benefit" {{ $item->tipe == 'Benefit' ? 'selected' : '' }}>Benefit</option>
                                                        <option value="Cost" {{ $item->tipe == 'Cost' ? 'selected' : '' }}>Cost</option>
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">Bobot</label>
                                                    <input type="number" step="0.01" name="bobot" class="form-control form-control-premium" value="{{ $item->bobot }}" required>
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
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-folder2-open d-block mb-2" style="font-size: 2rem;"></i>
                                Belum ada data kriteria.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahKriteria" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-premium">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center">
                    <i class="bi bi-plus-circle-fill text-primary me-2" style="font-size: 1.25rem;"></i>Tambah Kriteria Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('kriteria.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 text-start">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Kode Kriteria</label>
                        <input type="text" name="kode_kriteria" class="form-control form-control-premium" placeholder="Contoh: C1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Nama Kriteria</label>
                        <input type="text" name="nama_kriteria" class="form-control form-control-premium" placeholder="Contoh: Harga Tiket" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Tipe</label>
                            <select name="tipe" class="form-select form-select-custom" required>
                                <option value="Benefit">Benefit</option>
                                <option value="Cost">Cost</option>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Bobot</label>
                            <input type="number" step="0.01" name="bobot" class="form-control form-control-premium" placeholder="Contoh: 0.25" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-premium-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-premium-primary fw-bold">Simpan Kriteria</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection