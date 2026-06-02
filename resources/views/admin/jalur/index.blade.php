@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark">Master Data Jalur</h3>
            <p class="text-muted small">Kelola jalur pendakian tanpa desimal.</p>
        </div>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahJalur">
            <i class="bi bi-plus-lg me-2"></i> Tambah Jalur
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0"> {{-- Mengurangi padding agar tabel lebih luas --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" width="50">No</th>
                            <th>Gunung</th>
                            <th>Nama Jalur</th>
                            <th>Biaya Simaksi</th>
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
                            <td>Rp {{ number_format($item->biaya_simaksi, 0, ',', '.') }}</td>
                            <td>{{ $item->estimasi_jam }} Jam</td>
                            <td>
                                @php
                                    $badgeColor = $item->tingkat_kesulitan == 'Sulit' ? 'bg-danger' : ($item->tingkat_kesulitan == 'Sedang' ? 'bg-warning text-dark' : 'bg-success');
                                @endphp
                                <span class="badge {{ $badgeColor }} px-3">{{ $item->tingkat_kesulitan }}</span>
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEditJalur{{ $item->id }}" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    
                                    <form action="{{ route('jalur.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus jalur?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- MODAL EDIT JALUR --}}
                        <div class="modal fade" id="modalEditJalur{{ $item->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg">
                                    <div class="modal-header bg-info text-white">
                                        <h5 class="modal-title fw-bold">Edit Jalur: {{ $item->nama_jalur }}</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('jalur.update', $item->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-body p-4">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold text-dark">Pilih Gunung</label>
                                                <select name="gunung_id" class="form-select" required>
                                                    @foreach($gunungs as $g)
                                                        <option value="{{ $g->id }}" {{ $item->gunung_id == $g->id ? 'selected' : '' }}>{{ $g->nama_gunung }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold text-dark">Nama Jalur</label>
                                                <input type="text" name="nama_jalur" class="form-control" value="{{ $item->nama_jalur }}" required>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold text-dark">Biaya Simaksi</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" name="biaya_simaksi" class="form-control" value="{{ $item->biaya_simaksi }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold text-dark">Estimasi</label>
                                                    <div class="input-group">
                                                        <input type="number" name="estimasi_jam" class="form-control" value="{{ $item->estimasi_jam }}" required>
                                                        <span class="input-group-text">Jam</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-0">
                                                <label class="form-label fw-bold text-dark">Tingkat Kesulitan</label>
                                                <select name="tingkat_kesulitan" class="form-select" required>
                                                    <option value="Mudah" {{ $item->tingkat_kesulitan == 'Mudah' ? 'selected' : '' }}>Mudah</option>
                                                    <option value="Sedang" {{ $item->tingkat_kesulitan == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                                                    <option value="Sulit" {{ $item->tingkat_kesulitan == 'Sulit' ? 'selected' : '' }}>Sulit</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light border-top-0">
                                            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-info text-white px-4 fw-bold">Simpan Perubahan</button>
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
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">Tambah Jalur Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('jalur.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">Pilih Gunung</label>
                        <select name="gunung_id" class="form-select" required>
                            <option value="" disabled selected>-- Pilih Gunung --</option>
                            @foreach($gunungs as $g)
                                <option value="{{ $g->id }}">{{ $g->nama_gunung }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">Nama Jalur</label>
                        <input type="text" name="nama_jalur" class="form-control" required placeholder="Masukkan nama jalur...">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-dark">Biaya Simaksi</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="biaya_simaksi" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-dark">Estimasi</label>
                            <div class="input-group">
                                <input type="number" name="estimasi_jam" class="form-control" required>
                                <span class="input-group-text">Jam</span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold text-dark">Tingkat Kesulitan</label>
                        <select name="tingkat_kesulitan" class="form-select" required>
                            <option value="Mudah">Mudah</option>
                            <option value="Sedang">Sedang</option>
                            <option value="Sulit">Sulit</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">Simpan Jalur</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection