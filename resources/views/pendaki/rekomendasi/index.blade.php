@extends('layouts.admin')

@section('content')
<style>
    /* Reset padding wrapper template admin agar background abu menyentuh ujung layar */
    .content-wrapper, .main-content {
        padding: 0 !important;
    }

    /* Background & Card Setup - Diubah ke 100vh agar rata penuh ke bawah */
    .search-container {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        min-height: 100vh; /* KUNCI UTAMA: Mengubah 80vh menjadi 100vh */
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        box-sizing: border-box;
    }
    .modern-search-card {
        background: #ffffff;
        border: none;
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
        overflow: hidden;
        max-width: 900px;
        width: 100%;
        transition: transform 0.3s ease;
    }
    
    /* Header Section */
    .brand-header {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        padding: 35px;
        color: white;
        position: relative;
    }
    .brand-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #10b981, #3b82f6);
    }

    /* Form Styling */
    .form-body {
        padding: 40px;
    }
    .custom-input-group {
        position: relative;
        margin-bottom: 5px;
    }
    .custom-input-group .bi {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 1.2rem;
        z-index: 10;
        transition: color 0.3s ease;
    }
    .form-control-custom {
        padding: 14px 14px 14px 50px !important;
        border: 2px solid #e2e8f0 !important;
        border-radius: 16px !important;
        font-weight: 500;
        color: #334155;
        background-color: #f8fafc;
        transition: all 0.3s ease !important;
    }
    .form-control-custom:focus {
        border-color: #10b981 !important;
        background-color: #ffffff !important;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1) !important;
    }
    .form-control-custom:focus + .bi {
        color: #10b981;
    }
    
    .field-label {
        font-weight: 700;
        color: #1e293b;
        font-size: 0.9rem;
        margin-bottom: 8px;
        display: block;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Button Animations */
    .btn-search-custom {
        background: linear-gradient(90deg, #10b981 0%, #059669 100%);
        border: none;
        color: white;
        font-weight: 700;
        padding: 14px 30px;
        border-radius: 16px;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
    }
    .btn-search-custom:hover {
        background: linear-gradient(90deg, #059669 0%, #047857 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
        color: white;
    }
    .btn-cancel-custom {
        background-color: #f1f5f9;
        color: #64748b;
        font-weight: 600;
        padding: 14px 30px;
        border-radius: 16px;
        border: none;
        transition: all 0.3s ease;
    }
    .btn-cancel-custom:hover {
        background-color: #e2e8f0;
        color: #334155;
    }
</style>

<div class="search-container">
    <div class="card modern-search-card shadow-lg">
        {{-- Bagian Atas / Header Card --}}
        <div class="brand-header text-center text-md-start">
            <div class="d-md-flex align-items-center justify-content-between">
                <div>
                    <h3 class="fw-bold mb-1"><i class="bi bi-compass me-2 text-success"></i>Cari Rekomendasi Gunung</h3>
                    <p class="text-white-50 mb-0 small">Masukkan rencana budget, jumlah anggota kelompok, dan jadwal pendakian Anda.</p>
                </div>
                <div class="d-none d-md-block">
                    <i class="bi bi-mountains display-4 opacity-25"></i>
                </div>
            </div>
        </div>

        {{-- Bagian Utama Form Input --}}
        <form action="{{ route('rekomendasi.proses') }}" method="GET">
            <div class="form-body">
                <div class="row g-4">
                    {{-- Input 1: Budget Maksimal --}}
                    <div class="col-md-6">
                        <label class="field-label">Budget Maksimal Kelompok (Rp)</label>
                        <div class="custom-input-group">
                            <i class="bi bi-wallet2"></i>
                            <input type="number" 
                                   name="budget" 
                                   class="form-control form-control-custom" 
                                   placeholder="Contoh: 1500000" 
                                   required>
                        </div>
                        <small class="text-muted ms-2" style="font-size: 0.75rem;">Total alokasi biaya untuk seluruh anggota.</small>
                    </div>

                    {{-- Input 2: Jumlah Anggota --}}
                    <div class="col-md-6">
                        <label class="field-label">Jumlah Anggota (Orang)</label>
                        <div class="custom-input-group">
                            <i class="bi bi-people"></i>
                            <input type="number" 
                                   name="jumlah_anggota" 
                                   class="form-control form-control-custom" 
                                   placeholder="Contoh: 4" 
                                   min="1" 
                                   required>
                        </div>
                        <small class="text-muted ms-2" style="font-size: 0.75rem;">Termasuk Anda sendiri sebagai ketua/anggota.</small>
                    </div>

                    {{-- Input 3: Terminal Keberangkatan --}}
                    <div class="col-md-6">
                        <label class="field-label">Terminal Keberangkatan Asal</label>
                        <div class="custom-input-group">
                            <i class="bi bi-geo-alt"></i>
                            <select name="terminal_id" class="form-select form-control-custom" required>
                                <option value="" disabled selected>-- Pilih Terminal Asal Bus --</option>
                                @foreach($terminals as $terminal)
                                    <option value="{{ $terminal->id }}">{{ $terminal->nama_terminal }}</option>
                                @endforeach
                            </select>
                        </div>
                        <small class="text-muted ms-2" style="font-size: 0.75rem;">Lokasi titik kumpul keberangkatan armada bus.</small>
                    </div>

                    {{-- Input 4: Tanggal Keberangkatan (Diberi Batasan Min Tanggal Hari Ini) --}}
                    <div class="col-md-6">
                        <label class="field-label">Tanggal Rencana Keberangkatan</label>
                        <div class="custom-input-group">
                            <i class="bi bi-calendar-check"></i>
                            <input type="date" 
                                   name="tanggal_keberangkatan" 
                                   class="form-control form-control-custom" 
                                   min="{{ date('Y-m-d') }}"
                                   required>
                        </div>
                        <small class="text-muted ms-2" style="font-size: 0.75rem;">Menentukan penyesuaian tarif berdasarkan periode khusus.</small>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex flex-column flex-md-row justify-content-md-end gap-3 mt-5 border-top pt-4">
                    <button type="reset" class="btn btn-cancel-custom order-2 order-md-1">
                        <i class="bi bi-arrow-counterclockwise me-2"></i>Reset Form
                    </button>
                    <button type="submit" class="btn btn-search-custom order-1 order-md-2">
                        <i class="bi bi-search me-2"></i>Cari Rekomendasi Terbaik
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection