@extends('layouts.admin')

@section('content')

<div class="container-fluid p-4" style="background: #f8f9fc;">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Kelola Pengguna</h4>
            <p class="text-muted small mb-0">Manajemen akun admin dan pengguna sistem skripsi Anda.</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-primary shadow-sm px-4">
            <i class="bi bi-plus-lg me-2"></i>Tambah User
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary text-uppercase small" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        <tr>
                            <th class="ps-4" width="5%">No</th>
                            <th>Nama Lengkap</th>
                            <th>Username</th>
                            <th>Tanggal Terdaftar</th>
                            <th class="text-center" width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                        <tr>
                            <td class="ps-4 font-weight-bold text-secondary">{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3 bg-primary rounded-circle text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 1.2rem; font-weight: bold;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="fw-bold d-block text-dark">{{ $user->name }}</span>
                                        @if($user->id === auth()->user()->id)
                                            <span class="badge bg-soft-info text-info p-1 px-2" style="font-size: 0.65rem; background-color: #e0f7fa; border-radius: 50px;">
                                                <i class="bi bi-person-check me-1"></i>Sedang Login
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-secondary">{{ $user->username }}</td>
                            <td class="text-secondary">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    @if($user->id !== auth()->user()->id)
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda benar-benar yakin ingin menghapus user ini? Data tidak bisa dikembalikan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-sm btn-outline-secondary disabled" title="Tidak dapat menghapus akun yang sedang digunakan">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted bg-light">
                                <i class="bi bi-people-fill d-block mb-3 opacity-25" style="font-size: 4rem;"></i>
                                <h5 class="fw-bold">Belum Ada Data Pengguna</h5>
                                <p class="small">Silakan klik tombol "Tambah User" untuk membuat akun baru.</p>
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