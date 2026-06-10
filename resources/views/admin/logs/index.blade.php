@extends('layouts.admin')

@section('content')
<!-- Google Fonts: Outfit -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    .logs-body {
        font-family: 'Outfit', sans-serif;
        background-color: #f1f5f9;
        color: #1e293b;
    }

    .btn-premium-export {
        background: linear-gradient(135deg, #0284c7, #0369a1);
        border: none;
        border-radius: 12px;
        color: #ffffff !important;
        font-weight: 600;
        padding: 10px 20px;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.2);
        transition: all 0.3s ease;
        text-decoration: none;
    }
    .btn-premium-export:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(2, 132, 199, 0.35);
        filter: brightness(1.05);
    }

    .btn-premium-danger {
        background: linear-gradient(135deg, #ef4444, #b91c1c);
        border: none;
        border-radius: 12px;
        color: #ffffff !important;
        font-weight: 600;
        padding: 10px 20px;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
        transition: all 0.3s ease;
    }
    .btn-premium-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.35);
        filter: brightness(1.05);
    }

    .badge-tambah {
        background-color: #ecfdf5 !important;
        color: #10b981 !important;
        border: 1px solid rgba(16, 185, 129, 0.2) !important;
    }

    .badge-edit {
        background-color: #eff6ff !important;
        color: #3b82f6 !important;
        border: 1px solid rgba(59, 130, 246, 0.2) !important;
    }

    .badge-hapus {
        background-color: #fef2f2 !important;
        color: #ef4444 !important;
        border: 1px solid rgba(239, 68, 68, 0.2) !important;
    }

    .badge-auth {
        background-color: #faf5ff !important;
        color: #a855f7 !important;
        border: 1px solid rgba(168, 85, 247, 0.2) !important;
    }

    .badge-default {
        background-color: #f8fafc !important;
        color: #64748b !important;
        border: 1px solid rgba(100, 116, 139, 0.2) !important;
    }
</style>

<div class="logs-body container-fluid p-4">
    <!-- Success Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4 d-flex align-items-center" role="alert" style="background-color: #ecfdf5; border-left: 4px solid #10b981 !important; border-radius: 12px; color: #065f46;">
            <i class="bi bi-check-circle-fill me-3 fs-5" style="color: #10b981;"></i>
            <div class="fw-semibold">{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Error Alert -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4 d-flex align-items-center" role="alert" style="background-color: #fef2f2; border-left: 4px solid #ef4444 !important; border-radius: 12px; color: #991b1b;">
            <i class="bi bi-exclamation-triangle-fill me-3 fs-5" style="color: #ef4444;"></i>
            <div class="fw-semibold">{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1">Log Aktivitas Sistem</h3>
            <p class="text-muted small mb-0">Riwayat audit trail tindakan penambahan, pengubahan, penghapusan, dan sesi admin.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <!-- Tombol Ekspor CSV -->
            <a href="{{ route('admin.logs.export') }}" class="btn btn-premium-export d-flex align-items-center gap-2">
                <i class="bi bi-file-earmark-arrow-down-fill"></i>
                <span>Ekspor CSV</span>
            </a>
            
            <!-- Tombol Bersihkan Log (Hanya Superadmin) -->
            @if(auth()->check() && auth()->user()->role->name === 'Superadmin')
                <button type="button" class="btn btn-premium-danger d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#clearLogsModal">
                    <i class="bi bi-trash3-fill"></i>
                    <span>Bersihkan Log</span>
                </button>
            @endif
        </div>
    </div>

    <div class="card premium-card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle premium-table mb-0">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th class="ps-4" width="60">No</th>
                            <th width="200">Waktu &amp; Tanggal</th>
                            <th width="220">Admin Pelaku</th>
                            <th>Aktivitas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $key => $item)
                        <tr>
                            <td class="ps-4 text-muted fw-bold">{{ $logs->firstItem() + $key }}</td>
                            <td class="text-secondary small">
                                <i class="bi bi-clock-history me-1 text-primary"></i> 
                                {{ $item->created_at->translatedFormat('d M Y, H:i') }} WIB
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle p-2 me-2 d-flex align-items-center justify-content-center border" style="width: 36px; height: 36px;">
                                        <i class="bi bi-person text-info fs-5"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ $item->user->name ?? 'Sistem' }}</div>
                                        <div class="text-muted small">{{ '@' . ($item->user->username ?? 'system') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @php
                                    $text = $item->activity;
                                    $badgeClass = 'badge-default';
                                    $icon = 'bi-info-circle-fill';
                                    $badgeLabel = 'Lainnya';
                                    
                                    $loweredText = strtolower($text);
                                    if (str_contains($loweredText, 'menambahkan') || str_contains($loweredText, 'menginput') || str_contains($loweredText, 'buat')) {
                                        $badgeClass = 'badge-tambah';
                                        $icon = 'bi-plus-circle-fill';
                                        $badgeLabel = 'Tambah';
                                    } elseif (str_contains($loweredText, 'mengubah') || str_contains($loweredText, 'memperbarui') || str_contains($loweredText, 'menerapkan') || str_contains($loweredText, 'reset')) {
                                        $badgeClass = 'badge-edit';
                                        $icon = 'bi-pencil-fill';
                                        $badgeLabel = 'Edit';
                                    } elseif (str_contains($loweredText, 'menghapus') || str_contains($loweredText, 'membersihkan')) {
                                        $badgeClass = 'badge-hapus';
                                        $icon = 'bi-trash-fill';
                                        $badgeLabel = 'Hapus';
                                    } elseif (str_contains($loweredText, 'login') || str_contains($loweredText, 'logout')) {
                                        $badgeClass = 'badge-auth';
                                        $icon = 'bi-shield-lock-fill';
                                        $badgeLabel = 'Sesi';
                                    }
                                @endphp
                                <div class="d-flex align-items-center">
                                    <span class="badge {{ $badgeClass }} me-3 py-2 px-3 rounded-pill d-inline-flex align-items-center gap-1" style="font-size: 0.75rem; font-weight: 600;">
                                        <i class="bi {{ $icon }}"></i> {{ $badgeLabel }}
                                    </span>
                                    <span class="text-dark fw-medium" style="font-size: 0.95rem;">{{ $text }}</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <i class="bi bi-clock-history display-4 text-muted mb-3 d-block"></i>
                                <span class="text-muted fw-bold">Belum ada riwayat aktivitas log.</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Paginasi di bagian footer kartu -->
        @if($logs->hasPages())
            <div class="card-footer bg-white border-top py-3 px-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <div class="text-muted small">
                    Menampilkan <strong>{{ $logs->firstItem() }}</strong> sampai <strong>{{ $logs->lastItem() }}</strong> dari <strong>{{ $logs->total() }}</strong> entri
                </div>
                <div>
                    {{ $logs->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal Pembersihan Log (Hanya Superadmin) -->
@if(auth()->check() && auth()->user()->role->name === 'Superadmin')
<div class="modal fade" id="clearLogsModal" tabindex="-1" aria-labelledby="clearLogsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header bg-danger text-white py-3 border-0">
                <h5 class="modal-title fw-bold" id="clearLogsModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Konfirmasi Pembersihan Log
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.logs.clear') }}" method="POST" id="clearLogsForm">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <p class="text-dark fw-semibold mb-3" style="font-size: 0.95rem;">Pilih tipe pembersihan log aktivitas yang Anda inginkan:</p>
                    
                    <div class="p-3 mb-4" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px;">
                        <!-- Opsi 1: Log > 30 Hari -->
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="clear_type" id="clear_older" value="older_than_30_days" checked style="cursor: pointer;">
                            <label class="form-check-label fw-bold text-dark" for="clear_older" style="cursor: pointer; font-size: 0.9rem;">
                                Bersihkan Log Lama (&gt; 30 Hari)
                            </label>
                            <div class="text-muted small mt-1">
                                Hanya menghapus log aktivitas yang tercatat lebih dari 30 hari yang lalu. Pilihan ini direkomendasikan agar riwayat audit terbaru tetap terjaga.
                            </div>
                        </div>
                        
                        <hr class="my-3 text-secondary opacity-25">
                        
                        <!-- Opsi 2: Semua Log -->
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="clear_type" id="clear_all" value="all" style="cursor: pointer;">
                            <label class="form-check-label fw-bold text-danger" for="clear_all" style="cursor: pointer; font-size: 0.9rem;">
                                Bersihkan Seluruh Log
                            </label>
                            <div class="text-muted small mt-1">
                                Menghapus semua riwayat log tanpa terkecuali.
                            </div>
                        </div>
                    </div>

                    <!-- Input Konfirmasi Tambahan untuk Hapus Semua -->
                    <div id="confirmKeywordContainer" style="display: none;">
                        <div class="alert alert-warning border-0 p-3 mb-3 d-flex align-items-start gap-2" style="background-color: #fffbeb; color: #b45309; border-radius: 12px; font-size: 0.85rem;">
                            <i class="bi bi-exclamation-octagon-fill fs-5 mt-1" style="flex-shrink: 0;"></i>
                            <div>
                                Tindakan ini tidak dapat dibatalkan. Ketik kata kunci <strong>KONFIRMASI HAPUS</strong> di bawah untuk menyetujui pembersihan total.
                            </div>
                        </div>
                        <div class="mb-3">
                            <input type="text" id="confirm_keyword" class="form-control" placeholder="Ketik KONFIRMASI HAPUS..." autocomplete="off" style="border-radius: 12px; padding: 12px; border: 2px solid #cbd5e1; font-weight: 500;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light d-flex justify-content-between border-0 p-3">
                    <button type="button" class="btn btn-secondary px-4 py-2 border-0" style="border-radius: 12px; font-weight: 600; background-color: #e2e8f0; color: #475569;" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger px-4 py-2 border-0" id="btnSubmitClear" style="border-radius: 12px; font-weight: 600; background: linear-gradient(135deg, #ef4444, #dc2626); box-shadow: 0 4px 12px rgba(239,68,68,0.2);">Ya, Hapus Log</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const clearOlder = document.getElementById('clear_older');
    const clearAll = document.getElementById('clear_all');
    const confirmKeywordContainer = document.getElementById('confirmKeywordContainer');
    const confirmKeywordInput = document.getElementById('confirm_keyword');
    const btnSubmitClear = document.getElementById('btnSubmitClear');
    const clearForm = document.getElementById('clearLogsForm');

    function toggleKeywordVerification() {
        if (clearAll.checked) {
            confirmKeywordContainer.style.display = 'block';
            btnSubmitClear.disabled = true;
            confirmKeywordInput.required = true;
        } else {
            confirmKeywordContainer.style.display = 'none';
            btnSubmitClear.disabled = false;
            confirmKeywordInput.required = false;
            confirmKeywordInput.value = '';
        }
    }

    clearOlder.addEventListener('change', toggleKeywordVerification);
    clearAll.addEventListener('change', toggleKeywordVerification);
    
    confirmKeywordInput.addEventListener('input', function() {
        if (confirmKeywordInput.value === 'KONFIRMASI HAPUS') {
            btnSubmitClear.disabled = false;
        } else {
            btnSubmitClear.disabled = true;
        }
    });

    clearForm.addEventListener('submit', function(e) {
        if (clearAll.checked && confirmKeywordInput.value !== 'KONFIRMASI HAPUS') {
            e.preventDefault();
            alert('Silakan masukkan kata kunci konfirmasi dengan benar.');
        }
    });
});
</script>
@endif
@endsection
