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
        }
        .wrapper {
            display: flex;
            align-items: stretch;
        }
        #content {
            width: 100%;
            padding: 30px;
            min-height: 100vh;
            background-color: #f8f9fc;
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
            border-color: #0d6efd;
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
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
            border-color: #0d6efd;
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
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
            border-color: #3b82f6;
        }
        .dropdown-item-card.selected {
            background-color: #eff6ff;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
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
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
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
            border-color: #3b82f6;
            background-color: #ffffff;
            outline: none;
        }
        .btn-premium-primary {
            border-radius: 12px;
            font-weight: 600;
            padding: 10px 24px;
            transition: all 0.2s ease;
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
    @include('layouts.sidebar')

    <div id="content">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>