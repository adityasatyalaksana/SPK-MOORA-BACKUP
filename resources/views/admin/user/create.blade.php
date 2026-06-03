@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">
    <div class="card border-0 shadow-sm col-md-6 mx-auto">
        <div class="card-header bg-white py-3">
            <h5 class="m-0 font-weight-bold text-primary">Tambah User Baru</h5>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" value="{{ old('username') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tipe Akses (Role)</label>
                    <select name="role_id" class="form-select" required>
                        <option value="" disabled selected>-- Pilih Role --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Simpan User</button>
                    <a href="{{ route('users.index') }}" class="btn btn-light border">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection