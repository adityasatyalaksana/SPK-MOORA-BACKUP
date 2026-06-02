@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark">Master Data Gunung</h3>
        <a href="{{ route('gunung.create') }}" class="btn btn-success shadow-sm">
            <i class="bi bi-plus-lg me-2"></i> Tambah Gunung
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="50">No</th>
                            <th>Sampul</th>
                            <th>Nama Gunung</th>
                            <th>Lokasi</th>
                            <th>Ketinggian</th>
                            <th class="text-center" width="200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($gunungs as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                @if($item->gambar && count($item->gambar) > 0)
                                    {{-- Menggunakan Storage::url untuk memastikan path benar --}}
                                    <img src="{{ Storage::url($item->gambar[0]) }}" 
                                         class="rounded shadow-sm border" 
                                         width="70" height="45" 
                                         style="object-fit: cover;"
                                         onerror="this.onerror=null;this.src='https://placehold.co/100x100?text=Error';">
                                @else
                                    <span class="badge bg-secondary">No Image</span>
                                @endif
                            </td>
                            <td class="fw-bold text-success">{{ $item->nama_gunung }}</td>
                            <td><i class="bi bi-geo-alt text-danger"></i> {{ $item->lokasi }}</td>
                            <td>{{ number_format($item->ketinggian) }} MDPL</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#detailModal{{ $item->id }}" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    
                                    <a href="{{ route('gunung.edit', $item->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('gunung.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus {{ $item->nama_gunung }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header bg-light">
                                        <h5 class="modal-title fw-bold">Detail Gunung: {{ $item->nama_gunung }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="fw-bold text-muted small text-uppercase">Deskripsi</label>
                                            <p class="text-dark">{{ $item->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
                                        </div>
                                        <hr class="opacity-25">
                                        <h6 class="fw-bold mb-3"><i class="bi bi-images me-2"></i>Galeri Foto & Jalur</h6>
                                        <div class="row g-2">
                                            @if($item->gambar)
                                                @foreach($item->gambar as $img)
                                                    <div class="col-4">
                                                        <img src="{{ Storage::url($img) }}" 
                                                             class="img-fluid rounded border shadow-sm" 
                                                             style="height: 150px; width: 100%; object-fit: cover;"
                                                             onerror="this.onerror=null;this.src='https://placehold.co/300x200?text=Not+Found';">
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="col-12 text-center py-3 bg-light rounded">
                                                    <p class="text-muted small mb-0">Tidak ada gambar dalam galeri.</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-folder2-open d-block mb-2" style="font-size: 2rem;"></i>
                                Belum ada data gunung yang tersimpan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection