@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">
    <div class="mb-4">
        <h3 class="fw-bold text-dark">Tambah Data Gunung & Galeri</h3>
        <p class="text-muted">Gunakan halaman ini untuk mengunggah informasi gunung beserta foto profil, peta, dan jalur.</p>
    </div>

    <div class="card premium-card shadow-sm">
        <div class="card-body p-4">
            {{-- Alert Error jika validasi gagal --}}
            @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm mb-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('gunung.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6 text-start">
                        <label class="form-label fw-bold small text-muted text-uppercase">Nama Gunung</label>
                        <input type="text" name="nama_gunung" class="form-control form-control-premium" placeholder="Contoh: Gunung Rinjani" value="{{ old('nama_gunung') }}" required>
                    </div>
                    <div class="col-md-6 text-start">
                        <label class="form-label fw-bold small text-muted text-uppercase">Lokasi (Provinsi)</label>
                        <input type="text" name="lokasi" class="form-control form-control-premium" placeholder="Contoh: NTB" value="{{ old('lokasi') }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 text-start">
                        <label class="form-label fw-bold small text-muted text-uppercase">Ketinggian (MDPL)</label>
                        <div class="input-group">
                            <input type="number" name="ketinggian" class="form-control form-control-premium" placeholder="3726" value="{{ old('ketinggian') }}" required style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                            <span class="input-group-text bg-white border-2 border-start-0" style="border-color: #e2e8f0; border-top-right-radius: 12px; border-bottom-right-radius: 12px; font-weight: 600; color: #64748b; padding: 12px 16px;">MDPL</span>
                        </div>
                    </div>
                    <div class="col-md-6 text-start">
                        <label class="form-label fw-bold small text-muted text-uppercase">Upload Gambar (Bisa Banyak)</label>
                        {{-- Atribut 'multiple' dan 'gambar[]' adalah kunci untuk banyak foto --}}
                        <input type="file" name="gambar[]" class="form-control form-control-premium" accept="image/*" multiple required>
                        <small class="text-muted mt-1.5 d-block text-info" style="font-size: 0.78rem;">
                            <i class="bi bi-info-circle-fill me-1"></i> Tahan tombol <strong>Ctrl</strong> (Windows) atau <strong>Command</strong> (Mac) untuk memilih lebih dari satu foto.
                        </small>
                    </div>
                </div>

                <div class="mb-4 text-start">
                    <label class="form-label fw-bold small text-muted text-uppercase">Deskripsi Singkat</label>
                    <textarea name="deskripsi" class="form-control form-control-premium" rows="5" placeholder="Tuliskan deskripsi atau informasi penting tentang gunung ini...">{{ old('deskripsi') }}</textarea>
                </div>

                <hr class="my-4 text-secondary opacity-25">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.gunung.index') }}" class="btn btn-premium-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary btn-premium-primary shadow-sm">
                        <i class="bi bi-cloud-arrow-up me-2"></i> Simpan Data & Galeri
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection