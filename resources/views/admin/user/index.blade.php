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

    <div class="card premium-card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle premium-table">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th class="ps-4" width="5%">No</th>
                            <th>Nama Lengkap</th>
                            <th>Username</th>
                            <th>Tipe Akses (Role)</th>
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
                            <td>
                                @if($user->role)
                                    @php
                                        $isSuper = $user->role->name === 'Superadmin';
                                        $badgeBg = $isSuper ? 'bg-danger-subtle text-danger border-danger-subtle' : 'bg-success-subtle text-success border-success-subtle';
                                    @endphp
                                    <span class="badge {{ $badgeBg }} px-2.5 py-1.5 border" style="font-weight: 600; border-radius: 8px;">
                                        {{ $user->role->name }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2.5 py-1.5" style="font-weight: 600; border-radius: 8px;">
                                        -
                                    </span>
                                @endif
                            </td>
                            <td class="text-secondary">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius: 8px;" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    @if($user->id !== auth()->user()->id)
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda benar-benar yakin ingin menghapus user ini? Data tidak bisa dikembalikan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: 8px;" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-sm btn-outline-secondary disabled" style="border-radius: 8px;" title="Tidak dapat menghapus akun yang sedang digunakan">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
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

    {{-- Card untuk Mengatur Hak Akses --}}
    @if(auth()->user()->role && auth()->user()->role->name === 'Superadmin')
    <div class="card premium-card shadow-sm mt-4">
        <div class="card-header bg-dark text-white py-3 border-0">
            <h5 class="fw-bold text-white mb-1">Pengaturan Hak Akses Role</h5>
            <p class="text-light opacity-75 small mb-0">Tentukan kriteria menu yang dapat diakses oleh masing-masing tipe pengguna (role) secara dinamis.</p>
        </div>
        <div class="card-body p-0 border-top">
            <form action="{{ route('admin.users.update_permissions') }}" method="POST">
                @csrf
                <div class="table-responsive">
                    <table class="table table-hover align-middle premium-table">
                        <thead class="bg-dark text-white">
                            <tr>
                                <th class="ps-4" width="30%">Hak Akses (Menu)</th>
                                @foreach($roles as $role)
                                    <th class="text-center">{{ $role->name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($permissions as $permission)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold text-dark d-block" style="font-size: 0.9rem;">{{ $permission->label }}</span>
                                    <code class="text-muted" style="font-size: 0.75rem;">{{ $permission->name }}</code>
                                </td>
                                @foreach($roles as $role)
                                <td class="text-center">
                                    <div class="form-check form-switch d-inline-block">
                                        <input class="form-check-input" type="checkbox" role="switch" name="permissions[{{ $role->id }}][]" value="{{ $permission->id }}"
                                            {{ $role->permissions->contains('id', $permission->id) ? 'checked' : '' }}
                                            {{ $role->name === 'Superadmin' ? 'disabled' : '' }}>
                                    </div>
                                    @if($role->name === 'Superadmin')
                                        {{-- Hidden input so that Superadmin always retains all permissions when the form is submitted --}}
                                        <input type="hidden" name="permissions[{{ $role->id }}][]" value="{{ $permission->id }}">
                                    @endif
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4 bg-light border-top text-end">
                    <button type="submit" class="btn btn-success px-4" style="border-radius: 10px; font-weight: 600; background-color: #198754; border-color: #198754;">
                        <i class="bi bi-save me-2"></i>Simpan Perubahan Hak Akses
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>

<style>
    /* Utility colors for sub-gradients */
    .bg-success-subtle { background-color: #e8f5e9 !important; border-color: #c8e6c9 !important; }
    .bg-danger-subtle { background-color: #ffebee !important; border-color: #ffcdd2 !important; }
    .text-success { color: #157347 !important; }
    .text-danger { color: #dc3545 !important; }
</style>
@endsection