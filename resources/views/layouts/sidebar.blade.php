<style>
    #sidebar .nav-link i {
        color: rgba(255, 255, 255, 0.6) !important;
        transition: color 0.15s ease-in-out;
    }
    #sidebar .nav-link:hover i,
    #sidebar .nav-link.active i {
        color: #ffffff !important;
    }
</style>

<div id="sidebar" class="bg-dark text-white shadow" style="min-width: 280px; height: 100vh; position: sticky; top: 0; overflow-y: auto; align-self: flex-start;">
    <div class="p-4">
        <div class="text-center mb-4">
            <h4 class="fw-bold mt-2 mb-0" style="letter-spacing: 2px;">SPK MOORA</h4>
            <hr class="border-secondary mt-3">
        </div>
        
        <ul class="nav nav-pills flex-column mb-auto">
            
            <div class="small text-uppercase text-secondary fw-bold mb-2 px-3" style="font-size: 0.75rem; letter-spacing: 1px;">Menu Utama</div>
            
            <li class="nav-item mb-1">
                <a href="/" class="nav-link text-white py-2 d-flex align-items-center {{ Request::is('/') ? 'active bg-success' : '' }}">
                    <i class="bi bi-house me-3"></i> Beranda Pendaki
                </a>
            </li>

            <li class="nav-item mb-1">
                <a href="/profile-gunung" class="nav-link text-white py-2 d-flex align-items-center {{ Request::is('profile-gunung*') ? 'active bg-success' : '' }}">
                    <i class="bi bi-image me-3"></i> Profile Gunung
                </a>
            </li>

            <li class="nav-item mb-1">
                <a href="/cari-rekomendasi" class="nav-link text-white py-2 d-flex align-items-center {{ Request::is('cari-rekomendasi*') ? 'active bg-success' : '' }}">
                    <i class="bi bi-search me-3"></i> Cari Rekomendasi
                </a>
            </li>

            @auth
                <div class="small text-uppercase text-secondary fw-bold mt-4 mb-2 px-3" style="font-size: 0.75rem; letter-spacing: 1px;">Admin Panel</div>
                
                <li class="nav-item mb-1">
                    <a href="/admin/dashboard" class="nav-link text-white py-2 d-flex align-items-center {{ Request::is('admin/dashboard') ? 'active bg-success shadow' : '' }}">
                        <i class="bi bi-speedometer2 me-3"></i> Dashboard Admin
                    </a>
                </li>

                <div class="small text-uppercase text-secondary fw-bold mt-4 mb-2 px-3" style="font-size: 0.75rem; letter-spacing: 1px;">Master Data</div>
                
                <li class="nav-item mb-1">
                    <a href="/admin/gunung" class="nav-link text-white py-2 d-flex align-items-center {{ Request::is('admin/gunung*') ? 'active bg-success' : '' }}">
                        <i class="bi bi-layers me-3"></i> Data Gunung
                    </a>
                </li>

                <li class="nav-item mb-1">
                    <a href="/admin/terminal" class="nav-link text-white py-2 d-flex align-items-center {{ Request::is('admin/terminal*') ? 'active bg-success' : '' }}">
                        <i class="bi bi-bus-front me-3"></i> Data Terminal
                    </a>
                </li>

                <li class="nav-item mb-1">
                    <a href="/admin/jalur" class="nav-link text-white py-2 d-flex align-items-center {{ Request::is('admin/jalur*') ? 'active bg-success' : '' }}">
                        <i class="bi bi-signpost-2 me-3"></i> Data Jalur
                    </a>
                </li>

                <li class="nav-item mb-1">
                    <a href="/admin/biaya" class="nav-link text-white py-2 d-flex align-items-center {{ Request::is('admin/biaya*') ? 'active bg-success' : '' }}">
                        <i class="bi bi-wallet2 me-3"></i> Data Biaya
                    </a>
                </li>

                <div class="small text-uppercase text-secondary fw-bold mt-4 mb-2 px-3" style="font-size: 0.75rem; letter-spacing: 1px;">Metode Moora</div>
                
                <li class="nav-item mb-1">
                    <a href="/admin/kriteria" class="nav-link text-white py-2 d-flex align-items-center {{ Request::is('admin/kriteria*') ? 'active bg-success' : '' }}">
                        <i class="bi bi-list-check me-3"></i> Data Kriteria
                    </a>
                </li>

                {{-- Menu Baru: Data Sub-Kriteria --}}
                <li class="nav-item mb-1">
                    <a href="/admin/sub-kriteria" class="nav-link text-white py-2 d-flex align-items-center {{ Request::is('admin/sub-kriteria*') ? 'active bg-success' : '' }}">
                        <i class="bi bi-list-nested me-3"></i> Data Sub-Kriteria
                    </a>
                </li>

                <li class="nav-item mb-1">
                    <a href="/admin/penilaian" class="nav-link text-white py-2 d-flex align-items-center {{ Request::is('admin/penilaian*') ? 'active bg-success' : '' }}">
                        <i class="bi bi-pencil-square me-3"></i> Data Penilaian
                    </a>
                </li>

                <li class="nav-item mb-1">
                    <a href="/admin/hasil" class="nav-link text-white py-2 d-flex align-items-center {{ Request::is('admin/hasil*') ? 'active bg-success' : '' }}">
                        <i class="bi bi-calculator me-3"></i> Hasil Perhitungan
                    </a>
                </li>

                <div class="small text-uppercase text-secondary fw-bold mt-4 mb-2 px-3" style="font-size: 0.75rem; letter-spacing: 1px;">Pengaturan Sistem</div>

                <li class="nav-item mb-1">
                    <a href="/admin/users" class="nav-link text-white py-2 d-flex align-items-center {{ Request::is('admin/users*') ? 'active bg-success' : '' }}">
                        <i class="bi bi-people me-3"></i> Kelola User
                    </a>
                </li>

                <li class="nav-item mb-1">
                    <a href="/admin/logs" class="nav-link text-white py-2 d-flex align-items-center {{ Request::is('admin/logs*') ? 'active bg-success' : '' }}">
                        <i class="bi bi-clock-history me-3"></i> Log Aktivitas
                    </a>
                </li>
            @endauth
        </ul>

        <div class="mt-5 pt-4 border-top border-secondary">
            @auth
                <form action="/logout" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm w-100 py-2 d-flex align-items-center justify-content-center">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout Admin
                    </button>
                </form>
            @else
                <a href="/login" class="btn btn-outline-success btn-sm w-100 py-2 d-flex align-items-center justify-content-center">
                    <i class="bi bi-shield-lock me-2"></i> Login Admin
                </a>
            @endauth
        </div>
    </div>
</div>