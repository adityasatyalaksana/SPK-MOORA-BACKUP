<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SPK MOORA</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('storage/assets/gunung/bootstrap.css') }}">
    <style>
        body {
            background-color: #f8f9fc;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            overflow-x: hidden;
        }
        .wrapper {
            display: flex;
            align-items: stretch;
            min-height: 100vh;
            width: 100%;
        }
        #sidebar {
            min-width: 280px;
            max-width: 280px;
            height: 100vh;
            position: sticky;
            top: 0;
            display: flex;
            flex-direction: column;
            align-self: flex-start;
            transition: all 0.3s ease;
            overflow: hidden !important;
        }
        #sidebar .nav-link {
            white-space: normal !important;
            word-break: break-word;
        }
        .sidebar-menu-list {
            overflow-y: auto !important;
            overflow-x: hidden !important;
            flex-wrap: nowrap !important;
            flex: 1;
            min-height: 0;
            padding-right: 4px;
        }
        /* Custom scrollbar for sidebar menu */
        .sidebar-menu-list::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar-menu-list::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.03);
        }
        .sidebar-menu-list::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 4px;
        }
        .sidebar-menu-list::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        /* Premium Profile Card Styles */
        .premium-profile-card {
            background: rgba(255, 255, 255, 0.03) !important;
            border: 1px solid rgba(255, 255, 255, 0.06) !important;
            border-radius: 14px !important;
            padding: 12px 14px !important;
            transition: all 0.3s ease;
        }
        .premium-profile-card:hover {
            background: rgba(255, 255, 255, 0.05) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }
        .profile-avatar-gradient {
            background: linear-gradient(135deg, #10b981, #047857) !important;
            color: #ffffff !important;
            font-weight: 700;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
            flex-shrink: 0;
            font-size: 1.1rem;
        }
        .profile-name-text {
            font-weight: 600;
            color: #f8fafc !important;
            font-size: 0.9rem;
            letter-spacing: 0.3px;
        }
        .profile-role-badge {
            background: rgba(16, 185, 129, 0.1) !important;
            border: 1px solid rgba(16, 185, 129, 0.2) !important;
            color: #34d399 !important;
            font-size: 0.65rem !important;
            font-weight: 600 !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 2px 8px !important;
            border-radius: 6px !important;
            display: inline-block;
        }
        .btn-logout-premium {
            background: rgba(239, 68, 68, 0.1) !important;
            border: 1px solid rgba(239, 68, 68, 0.15) !important;
            color: #f87171 !important;
            border-radius: 50% !important;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease-in-out !important;
            cursor: pointer;
            padding: 0 !important;
        }
        .btn-logout-premium:hover {
            background: #ef4444 !important;
            border-color: #ef4444 !important;
            color: #ffffff !important;
            box-shadow: 0 0 12px rgba(239, 68, 68, 0.4);
            transform: scale(1.05);
        }
        #content {
            width: 100%;
            padding: 30px;
            min-height: 100vh;
            background-color: #f8f9fc;
            transition: all 0.3s ease;
        }

        /* Mobile Responsive Styles for Layout */
        @media (max-width: 991.98px) {
            .wrapper {
                flex-direction: column;
            }
            #sidebar {
                position: fixed !important;
                left: -280px;
                top: 0;
                bottom: 0;
                z-index: 1040;
                transition: all 0.3s ease-in-out;
                box-shadow: 0 0 20px rgba(15, 23, 42, 0.15);
            }
            #sidebar.show {
                left: 0;
            }
            #content {
                padding: 20px;
                min-height: calc(100vh - 62px);
            }
            .sidebar-backdrop {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(15, 23, 42, 0.5);
                z-index: 1030;
                backdrop-filter: blur(4px);
                display: none;
            }
            .sidebar-backdrop.show {
                display: block;
            }
        }

        /* Premium Theme Helper Classes */
        .premium-card {
            border-radius: 16px;
            overflow: hidden;
            border: none;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.03);
            background: #ffffff;
        }
        .premium-table {
            margin-bottom: 0;
        }
        .premium-table th {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.75px;
            font-weight: 700;
            vertical-align: middle;
            padding: 16px 12px;
            border-bottom: none;
            background-color: #0f172a;
            color: #ffffff;
        }
        .premium-table td {
            vertical-align: middle;
            padding: 16px 12px;
            border-color: #f1f5f9;
            color: #334155;
        }
        .premium-table tbody tr {
            transition: all 0.2s ease;
        }
        .premium-table tbody tr:hover {
            background-color: #f8fafc;
        }
        .modal-premium {
            border-radius: 20px;
            overflow: hidden;
            border: none;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        }
        .modal-premium .modal-header {
            border-bottom: none;
            padding: 24px;
        }
        .modal-premium .modal-body {
            padding: 24px;
        }
        .modal-premium .modal-footer {
            border-top: none;
            padding: 20px 24px;
            background-color: #f8fafc;
        }
        .form-control-premium {
            padding: 12px 16px;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            font-weight: 500;
            color: #334155;
            transition: all 0.3s ease;
            background-color: #f8fafc;
        }
        .form-control-premium:focus {
            border-color: #10b981;
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
            outline: none;
        }
        .form-select-custom {
            padding: 12px 16px;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            font-weight: 500;
            color: #334155;
            transition: all 0.3s ease;
            background-color: #f8fafc;
        }
        .form-select-custom:focus {
            border-color: #10b981;
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
            outline: none;
        }
        /* Custom Searchable Dropdown Styles */
        .dropdown-item-card {
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid #e2e8f0;
            background-color: #ffffff;
            text-align: left;
        }
        .dropdown-item-card:hover {
            background-color: #f8fafc;
            border-color: #10b981;
        }
        .dropdown-item-card.selected {
            background-color: #ecfdf5;
            border-color: #10b981;
        }
        .custom-dropdown-menu {
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
            border: 1px solid #e2e8f0 !important;
            background-color: #ffffff;
        }
        .search-input {
            border-radius: 10px;
            padding: 10px 14px;
            border: 2px solid #e2e8f0;
        }
        .search-input:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
            outline: none;
        }
        .dropdown-btn-custom {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            background-color: #f8fafc;
            transition: all 0.2s ease;
        }
        .dropdown-btn-custom:hover, .dropdown-btn-custom:focus {
            border-color: #10b981;
            background-color: #ffffff;
            outline: none;
        }
        /* Override Bootstrap Primary Buttons & custom premium primary */
        .btn-primary, .btn-premium-primary {
            background-color: #10b981 !important;
            border-color: #10b981 !important;
            color: #ffffff !important;
            border-radius: 12px;
            font-weight: 600;
            padding: 10px 24px;
            transition: all 0.2s ease;
        }
        .btn-primary:hover, .btn-primary:focus, .btn-primary:active, .btn-primary:active:focus,
        .btn-premium-primary:hover, .btn-premium-primary:focus {
            background-color: #059669 !important;
            border-color: #059669 !important;
            color: #ffffff !important;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15) !important;
        }
        .btn-premium-secondary {
            border-radius: 12px;
            font-weight: 600;
            padding: 10px 24px;
            background-color: #e2e8f0;
            border: none;
            color: #334155;
            transition: all 0.2s ease;
        }
        .btn-premium-secondary:hover {
            background-color: #cbd5e1;
            color: #1e293b;
        }
    </style>
</head>
<body>

<div class="wrapper">
    <!-- Backdrop for Mobile Sidebar -->
    <div class="sidebar-backdrop" id="sidebar-backdrop"></div>

    @include('layouts.sidebar')

    <div class="main-container d-flex flex-column flex-grow-1" style="min-width: 0; min-height: 100vh;">
        <!-- Top Navbar -->
        <header class="top-navbar bg-white border-bottom px-4 py-3 d-flex d-lg-none align-items-center justify-content-between shadow-sm position-sticky top-0 d-print-none" style="z-index: 1020; height: 70px;">
            <!-- Left side: Hamburger (Mobile) or Breadcrumbs (Desktop) -->
            <div class="d-flex align-items-center">
                <button class="btn btn-light border d-lg-none me-3 p-2 d-flex align-items-center justify-content-center" id="sidebar-toggle" style="border-radius: 8px;">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <span class="fs-5 fw-bold text-dark d-lg-none">SPK MOORA</span>
                
                <div class="d-none d-lg-flex align-items-center gap-2">
                    <i class="bi bi-grid-fill text-success fs-5 me-1"></i>
                    <span class="text-secondary small">Admin Panel</span>
                    <i class="bi bi-chevron-right text-muted" style="font-size: 0.75rem;"></i>
                    <span class="text-dark fw-bold small">Sistem Pendukung Keputusan</span>
                </div>
            </div>



        </header>

        <!-- Content Area -->
        <div id="content">
            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('sidebar');
    const backdrop = document.getElementById('sidebar-backdrop');

    if (sidebarToggle && sidebar && backdrop) {
        sidebarToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            sidebar.classList.toggle('show');
            backdrop.classList.toggle('show');
        });

        backdrop.addEventListener('click', function() {
            sidebar.classList.remove('show');
            backdrop.classList.remove('show');
        });

        // Close sidebar on navigation links click (mobile)
        const navLinks = sidebar.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 992) {
                    sidebar.classList.remove('show');
                    backdrop.classList.remove('show');
                }
            });
        });
    }
});
</script>
</body>
</html>