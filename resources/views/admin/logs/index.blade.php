@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark">Log Aktivitas Sistem</h3>
            <p class="text-muted small">Riwayat tindakan penambahan, pengubahan, dan penghapusan data oleh Admin.</p>
        </div>
    </div>

    <div class="card premium-card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle premium-table">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th class="ps-4" width="50">No</th>
                            <th width="180">Waktu &amp; Tanggal</th>
                            <th width="200">Admin Pelaku</th>
                            <th>Aktivitas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $key => $item)
                        <tr>
                            <td class="ps-4">{{ $key + 1 }}</td>
                            <td class="text-muted small">
                                <i class="bi bi-calendar3 me-1"></i> {{ $item->created_at->translatedFormat('d M Y, H:i') }} WIB
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle p-2 me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                        <i class="bi bi-person text-info"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ $item->user->name ?? 'System' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @php
                                    $text = $item->activity;
                                    $badge = '<span class="badge bg-secondary me-2">Lainnya</span>';
                                    if (str_contains(strtolower($text), 'menambahkan') || str_contains(strtolower($text), 'menginput')) {
                                        $badge = '<span class="badge bg-success-subtle text-success border border-success-subtle me-2"><i class="bi bi-plus-circle-fill me-1"></i> Tambah</span>';
                                    } elseif (str_contains(strtolower($text), 'mengubah') || str_contains(strtolower($text), 'memperbarui') || str_contains(strtolower($text), 'menerapkan') || str_contains(strtolower($text), 'reset')) {
                                        $badge = '<span class="badge bg-info-subtle text-info border border-info-subtle me-2"><i class="bi bi-pencil-fill me-1"></i> Edit</span>';
                                    } elseif (str_contains(strtolower($text), 'menghapus')) {
                                        $badge = '<span class="badge bg-danger-subtle text-danger border border-danger-subtle me-2"><i class="bi bi-trash-fill me-1"></i> Hapus</span>';
                                    }
                                @endphp
                                <div class="d-flex align-items-center">
                                    {!! $badge !!}
                                    <span class="text-dark fw-medium" style="font-size: 0.95rem;">{{ $text }}</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <i class="bi bi-clock-history display-4 text-muted mb-3 d-block"></i>
                                <span class="text-muted">Belum ada riwayat aktivitas log.</span>
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
