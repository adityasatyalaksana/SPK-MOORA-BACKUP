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

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
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
                                <span class="badge bg-primary-subtle text-primary">
                                    {{ $s->kriteria->kode_kriteria }} - {{ $s->kriteria->nama_kriteria }}
                                </span>
                            </td>
                            <td class="fw-bold">{{ $s->nama_sub }}</td>
                            <td class="text-center">
                                <span class="badge bg-dark">{{ $s->bobot }}</span>
                            </td>
                             <td class="text-center">
                                 <div class="d-flex justify-content-center gap-2">
                                     <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $s->id }}" title="Edit">
                                         <i class="bi bi-pencil-square"></i>
                                     </button>
                                     <form action="{{ route('sub-kriteria.destroy', $s->id) }}" method="POST">
                                         @csrf
                                         @method('DELETE')
                                         <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus data ini?')" title="Hapus">
                                             <i class="bi bi-trash"></i>
                                         </button>
                                     </form>
                                 </div>
                             </td>
                        </tr>

                        {{-- MODAL EDIT --}}
                        <div class="modal fade" id="modalEdit{{ $s->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg">
                                    <div class="modal-header bg-info text-white">
                                        <h5 class="modal-title fw-bold">Edit Sub-Kriteria</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('sub-kriteria.update', $s->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body p-4">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Pilih Kriteria</label>
                                                <select name="kriteria_id" class="form-select" required>
                                                    @foreach($kriterias as $k)
                                                        <option value="{{ $k->id }}" {{ $s->kriteria_id == $k->id ? 'selected' : '' }}>
                                                            {{ $k->kode_kriteria }} - {{ $k->nama_kriteria }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Nama Sub-Kriteria</label>
                                                <input type="text" name="nama_sub" class="form-control" value="{{ $s->nama_sub }}" required placeholder="Contoh: Sangat Sulit">
                                            </div>
                                            <div class="mb-0">
                                                <label class="form-label fw-bold">Bobot Nilai</label>
                                                <input type="number" name="bobot" class="form-control" value="{{ $s->bobot }}" required placeholder="Contoh: 5">
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light">
                                            <button type="submit" class="btn btn-info text-white w-100 fw-bold">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Belum ada data sub-kriteria.</td>
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
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">Tambah Sub-Kriteria Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('sub-kriteria.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Kriteria Utama</label>
                        <select name="kriteria_id" class="form-select" required>
                            <option value="">-- Pilih Kriteria --</option>
                            @foreach($kriterias as $k)
                                <option value="{{ $k->id }}">{{ $k->kode_kriteria }} - {{ $k->nama_kriteria }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Sub-Kriteria</label>
                        <input type="text" name="nama_sub" class="form-control" required placeholder="Contoh: Murah / Sulit / Sangat Dekat">
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold">Bobot Nilai (Angka)</label>
                        <input type="number" name="bobot" class="form-control" required placeholder="Contoh: 1-5">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection