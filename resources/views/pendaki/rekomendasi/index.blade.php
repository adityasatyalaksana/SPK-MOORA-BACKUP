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

    /* Custom Dropdown Overrides */
    .dropdown-btn-custom:focus, .dropdown-btn-custom.open-dropdown {
        border-color: #10b981 !important;
        background-color: #ffffff !important;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1) !important;
    }
    .dropdown-btn-custom.open-dropdown i.bi-geo-alt {
        color: #10b981 !important;
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
                            <input type="hidden" name="budget" id="budget-hidden">
                            <input type="text" 
                                   id="budget-display" 
                                   class="form-control form-control-custom" 
                                   placeholder="Contoh: 1.500.000" 
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

                    {{-- Input 3: Terminal Keberangkatan (Custom Searchable Dropdown) --}}
                    <div class="col-md-6 position-relative">
                        <label class="field-label">Terminal Keberangkatan Asal</label>
                        <input type="hidden" name="terminal_id" id="selected-terminal-id" required>
                        
                        <button type="button" class="btn dropdown-btn-custom text-start d-flex justify-content-between align-items-center w-100" id="terminal-dropdown-btn" style="padding: 14px 18px 14px 50px !important; border: 2px solid #e2e8f0 !important; border-radius: 16px !important; background-color: #f8fafc; font-weight: 500; position: relative;">
                            <i class="bi bi-geo-alt" style="position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 1.2rem;"></i>
                            <span id="terminal-dropdown-label" class="text-muted small">-- Pilih Terminal Asal Bus --</span>
                            <i class="bi bi-chevron-down text-secondary"></i>
                        </button>
                        
                        <div class="custom-dropdown-menu d-none position-absolute bg-white shadow-lg border rounded-3 p-3 mt-1" id="terminal-dropdown-menu" style="z-index: 1050; left: 12px; right: 12px; max-height: 280px; overflow-y: auto;">
                            <input type="text" class="form-control mb-3 search-input" placeholder="Cari terminal keberangkatan...">
                            <div class="dropdown-list-items">
                                @foreach($terminals as $terminal)
                                    @php
                                        $cleanName = str_ireplace('Terminal ', '', $terminal->nama_terminal);
                                    @endphp
                                    <div class="dropdown-item-card p-3 mb-2 rounded-3" 
                                         data-id="{{ $terminal->id }}" 
                                         data-search="{{ strtolower($cleanName) }} {{ strtolower($terminal->lokasi) }}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="fw-bold text-dark d-block"><i class="bi bi-geo-alt-fill text-danger me-2"></i>{{ $terminal->nama_terminal }}</span>
                                                <span class="text-muted small"><i class="bi bi-map me-1"></i>{{ $terminal->lokasi }}</span>
                                            </div>
                                            <span class="badge bg-primary-subtle text-primary">{{ $terminal->tipe }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <small class="text-muted ms-2 mt-1 d-block" style="font-size: 0.75rem;">Lokasi titik kumpul keberangkatan armada bus.</small>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const budgetDisplay = document.getElementById('budget-display');
        const budgetHidden = document.getElementById('budget-hidden');

        function formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function updateValues() {
            // Remove all non-digits
            let rawValue = budgetDisplay.value.replace(/\D/g, '');
            budgetHidden.value = rawValue;
            
            if (rawValue) {
                budgetDisplay.value = formatNumber(rawValue);
            } else {
                budgetDisplay.value = '';
            }
        }

        budgetDisplay.addEventListener('input', updateValues);

        // Custom Dropdown Terminal Logic
        const termBtn = document.getElementById('terminal-dropdown-btn');
        const termLabel = document.getElementById('terminal-dropdown-label');
        const termMenu = document.getElementById('terminal-dropdown-menu');
        const termHiddenInput = document.getElementById('selected-terminal-id');
        const termSearchInput = termMenu.querySelector('.search-input');
        const termItems = termMenu.querySelectorAll('.dropdown-item-card');

        // Toggle menu visibility
        termBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            termMenu.classList.toggle('d-none');
            termBtn.classList.toggle('open-dropdown');
            if (!termMenu.classList.contains('d-none')) {
                termSearchInput.focus();
            }
        });

        // Hide menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!termBtn.contains(e.target) && !termMenu.contains(e.target)) {
                termMenu.classList.add('d-none');
                termBtn.classList.remove('open-dropdown');
            }
        });

        // Handle item selection
        termItems.forEach(function(item) {
            item.addEventListener('click', function() {
                // Clear selection
                termItems.forEach(i => i.classList.remove('selected'));
                
                // Set selected state
                item.classList.add('selected');
                
                // Set hidden input value
                termHiddenInput.value = item.getAttribute('data-id');
                
                // Update button text
                const titleText = item.querySelector('.fw-bold').textContent.trim();
                termLabel.innerHTML = `<strong>${titleText}</strong>`;
                termLabel.classList.remove('text-muted');
                termLabel.classList.add('text-dark');

                // Close menu
                termMenu.classList.add('d-none');
                termBtn.classList.remove('open-dropdown');
            });
        });

        // Handle search filtering
        termSearchInput.addEventListener('input', function() {
            const query = termSearchInput.value.toLowerCase().trim();
            termItems.forEach(function(item) {
                const searchData = item.getAttribute('data-search');
                if (searchData.includes(query)) {
                    item.style.setProperty('display', 'block', 'important');
                } else {
                    item.style.setProperty('display', 'none', 'important');
                }
            });
        });

        // Handle form reset
        const form = budgetDisplay.closest('form');
        if (form) {
            form.addEventListener('reset', function() {
                setTimeout(() => {
                    budgetHidden.value = '';
                    budgetDisplay.value = '';
                    
                    // Reset custom terminal selection
                    termHiddenInput.value = '';
                    termLabel.innerHTML = '-- Pilih Terminal Asal Bus --';
                    termLabel.classList.add('text-muted');
                    termLabel.classList.remove('text-dark');
                    termBtn.classList.remove('open-dropdown');
                    termMenu.classList.add('d-none');
                    termItems.forEach(i => i.classList.remove('selected'));
                }, 10);
            });

            // Form Submit Validation for custom dropdown
            form.addEventListener('submit', function(e) {
                if (!termHiddenInput.value) {
                    e.preventDefault();
                    alert('Silakan pilih Terminal Keberangkatan Asal terlebih dahulu.');
                    termBtn.focus();
                    termBtn.click();
                }
            });
        }

        // Initialize if there is already a value (e.g. from history back/redirect back)
        if (budgetDisplay.value) {
            updateValues();
        }
    });
</script>
@endsection