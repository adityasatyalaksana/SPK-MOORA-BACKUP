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

<div id="sidebar" class="bg-dark text-white shadow">
    <div class="p-4 d-flex flex-column h-100">
        <div class="text-center mb-4 flex-shrink-0">
            <h4 class="fw-bold mt-2 mb-0" style="letter-spacing: 2px;">SPK MOORA</h4>
            <hr class="border-secondary mt-3">
        </div>
        
        <ul class="nav nav-pills flex-column sidebar-menu-list mb-3">
            
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
                        <i class="bi bi-speedometer2 me-3"></i> Dashboard
                    </a>
                </li>

                @canany(['manage_gunung', 'manage_terminal', 'manage_jalur', 'manage_biaya'])
                    <div class="small text-uppercase text-secondary fw-bold mt-4 mb-2 px-3" style="font-size: 0.75rem; letter-spacing: 1px;">Master Data</div>
                    
                    @can('manage_gunung')
                    <li class="nav-item mb-1">
                        <a href="/admin/gunung" class="nav-link text-white py-2 d-flex align-items-center {{ Request::is('admin/gunung*') ? 'active bg-success' : '' }}">
                            <i class="bi bi-layers me-3"></i> Data Gunung
                        </a>
                    </li>
                    @endcan

                    @can('manage_terminal')
                    <li class="nav-item mb-1">
                        <a href="/admin/terminal" class="nav-link text-white py-2 d-flex align-items-center {{ Request::is('admin/terminal*') ? 'active bg-success' : '' }}">
                            <i class="bi bi-bus-front me-3"></i> Data Terminal
                        </a>
                    </li>
                    @endcan

                    @can('manage_jalur')
                    <li class="nav-item mb-1">
                        <a href="/admin/jalur" class="nav-link text-white py-2 d-flex align-items-center {{ Request::is('admin/jalur*') ? 'active bg-success' : '' }}">
                            <i class="bi bi-signpost-2 me-3"></i> Data Jalur
                        </a>
                    </li>
                    @endcan

                    @can('manage_biaya')
                    <li class="nav-item mb-1">
                        <a href="/admin/biaya" class="nav-link text-white py-2 d-flex align-items-center {{ Request::is('admin/biaya*') ? 'active bg-success' : '' }}">
                            <i class="bi bi-wallet2 me-3"></i> Data Biaya
                        </a>
                    </li>
                    @endcan
                @endcanany

                @canany(['manage_kriteria', 'manage_sub_kriteria', 'manage_penilaian', 'view_hasil'])
                    <div class="small text-uppercase text-secondary fw-bold mt-4 mb-2 px-3" style="font-size: 0.75rem; letter-spacing: 1px;">Metode Moora</div>
                    
                    @can('manage_kriteria')
                    <li class="nav-item mb-1">
                        <a href="/admin/kriteria" class="nav-link text-white py-2 d-flex align-items-center {{ Request::is('admin/kriteria*') ? 'active bg-success' : '' }}">
                            <i class="bi bi-list-check me-3"></i> Data Kriteria
                        </a>
                    </li>
                    @endcan

                    @can('manage_sub_kriteria')
                    <li class="nav-item mb-1">
                        <a href="/admin/sub-kriteria" class="nav-link text-white py-2 d-flex align-items-center {{ Request::is('admin/sub-kriteria*') ? 'active bg-success' : '' }}">
                            <i class="bi bi-list-nested me-3"></i> Data Sub-Kriteria
                        </a>
                    </li>
                    @endcan

                    @can('manage_penilaian')
                    <li class="nav-item mb-1">
                        <a href="/admin/penilaian" class="nav-link text-white py-2 d-flex align-items-center {{ Request::is('admin/penilaian*') ? 'active bg-success' : '' }}">
                            <i class="bi bi-pencil-square me-3"></i> Data Penilaian
                        </a>
                    </li>
                    @endcan

                    @can('view_hasil')
                    <li class="nav-item mb-1">
                        <a href="/admin/hasil" class="nav-link text-white py-2 d-flex align-items-center {{ Request::is('admin/hasil*') ? 'active bg-success' : '' }}">
                            <i class="bi bi-calculator me-3"></i> Hasil Perhitungan
                        </a>
                    </li>
                    @endcan
                @endcanany

                @canany(['manage_users', 'view_logs'])
                    <div class="small text-uppercase text-secondary fw-bold mt-4 mb-2 px-3" style="font-size: 0.75rem; letter-spacing: 1px;">Pengaturan Sistem</div>

                    @can('manage_users')
                        <li class="nav-item mb-1">
                            <a href="/admin/users" class="nav-link text-white py-2 d-flex align-items-center {{ Request::is('admin/users*') ? 'active bg-success' : '' }}">
                                <i class="bi bi-people me-3"></i> Kelola User
                            </a>
                        </li>
                    @endcan

                    @can('view_logs')
                        <li class="nav-item mb-1">
                            <a href="/admin/logs" class="nav-link text-white py-2 d-flex align-items-center {{ Request::is('admin/logs*') ? 'active bg-success' : '' }}">
                                <i class="bi bi-clock-history me-3"></i> Log Aktivitas
                            </a>
                        </li>
                    @endcan
                @endcanany
            @endauth
        </ul>

        <div class="mt-auto pt-4 border-top border-secondary flex-shrink-0 mb-3">
            @auth
                <div class="d-flex align-items-center justify-content-between premium-profile-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="profile-avatar-gradient">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="text-start" style="line-height: 1.25;">
                            <span class="d-block profile-name-text text-truncate" style="max-width: 135px;" title="{{ auth()->user()->name }}">{{ auth()->user()->name }}</span>
                            <span class="profile-role-badge mt-1">
                                {{ auth()->user()->role->name ?? 'User' }}
                            </span>
                        </div>
                    </div>
                    <form action="/logout" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-logout-premium" title="Logout dari Sistem">
                            <i class="bi bi-power" style="font-size: 1.1rem;"></i>
                        </button>
                    </form>
                </div>
            @else
                <a href="/login" class="btn btn-outline-success btn-sm w-100 py-2 d-flex align-items-center justify-content-center" style="border-radius: 8px;">
                    <i class="bi bi-shield-lock me-2"></i> Login Admin
                </a>
            @endauth
        </div>
    </div>
</div>