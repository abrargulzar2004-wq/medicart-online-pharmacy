<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - MediCart</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --admin-navy: #0F172A;
            --admin-navy-light: #1E293B;
            --admin-accent: #2563EB;
            --admin-accent-dark: #1D4ED8;
            --admin-bg: #F8FAFC;
            --admin-text: #334155;
            --admin-muted: #64748B;
            --admin-border: #E2E8F0;
            --admin-white: #FFFFFF;
            --admin-success: #059669;
            --admin-danger: #DC2626;
            --admin-radius: 0.375rem;
        }

        body.dark-mode {
            --admin-navy: #0F172A;
            --admin-navy-light: #1E293B;
            --admin-bg: #0F172A;
            --admin-text: #E2E8F0;
            --admin-muted: #94A3B8;
            --admin-border: #334155;
            --admin-white: #1E293B;
        }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: var(--admin-bg);
            color: var(--admin-text);
            display: flex;
            height: 100vh;
            overflow: hidden;
            transition: background-color 0.2s, color 0.2s;
        }

        /* Dark mode overrides */
        body.dark-mode .topbar {
            border-bottom: 1px solid var(--admin-border);
        }
        body.dark-mode [style*="background: #fff"],
        body.dark-mode [style*="background:#fff"],
        body.dark-mode [style*="background: #ffffff"],
        body.dark-mode .admin-card {
            background: var(--admin-white) !important;
            color: var(--admin-text) !important;
            border-color: var(--admin-border) !important;
        }
        body.dark-mode table, body.dark-mode tr, body.dark-mode td {
            border-color: var(--admin-border) !important;
            color: var(--admin-text) !important;
        }
        body.dark-mode thead {
            background: var(--admin-navy) !important;
            color: #fff !important;
        }
        body.dark-mode th { color: #fff !important; }
        body.dark-mode [style*="color: #2c3e50"], body.dark-mode [style*="color:#2c3e50"],
        body.dark-mode [style*="color: #333"], body.dark-mode [style*="color:#333"],
        body.dark-mode [style*="color: #000"], body.dark-mode [style*="color:#000"],
        body.dark-mode [style*="color: black"], body.dark-mode [style*="color:black"],
        body.dark-mode [style*="color: #555"], body.dark-mode [style*="color:#555"] {
            color: var(--admin-text) !important;
        }
        body.dark-mode [style*="color: #7f8c8d"], body.dark-mode [style*="color:#7f8c8d"] {
            color: var(--admin-muted) !important;
        }
        body.dark-mode h1, body.dark-mode h2, body.dark-mode h3,
        body.dark-mode h4, body.dark-mode h5, body.dark-mode h6 {
            color: #F8FAFC !important;
        }
        body.dark-mode .topbar { color: var(--admin-text) !important; }

        /* Compact Sidebar */
        body.compact-sidebar .sidebar { width: 60px; }
        body.compact-sidebar .sidebar-header {
            font-size: 10px;
            padding: 20px 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        body.compact-sidebar .sidebar-nav a {
            padding: 15px 5px;
            text-align: center;
            font-size: 10px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .sidebar {
            transition: width 0.2s;
            width: 250px;
            flex-shrink: 0;
            overflow-x: hidden;
        }

        /* Sidebar — Deep Navy */
        .sidebar {
            background-color: var(--admin-navy);
            color: #E2E8F0;
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgba(255, 255, 255, 0.06);
        }
        .sidebar-header {
            padding: 1.25rem 1rem;
            font-size: 1.125rem;
            font-weight: 700;
            text-align: center;
            background-color: var(--admin-navy);
            letter-spacing: -0.01em;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            color: #FFFFFF;
            flex-shrink: 0;
        }
        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            overflow-y: auto;
            flex-grow: 1;
            width: 100%;
        }
        .sidebar-nav li {
            width: 100%;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }
        .sidebar-nav a {
            display: block;
            width: 100%;
            box-sizing: border-box;
            padding: 0.875rem 1.25rem;
            color: #CBD5E1;
            text-decoration: none;
            transition: background 0.2s, color 0.2s, border-color 0.2s;
            font-size: 0.9375rem;
            font-weight: 500;
            border-left: 3px solid transparent;
        }
        .sidebar-nav a::after {
            display: none;
        }
        .sidebar-nav a:hover {
            background-color: var(--admin-navy-light);
            color: #FFFFFF;
            border-left-color: var(--admin-accent);
        }
        .sidebar-nav a.active {
            background-color: var(--admin-navy-light);
            color: #FFFFFF;
            border-left-color: var(--admin-accent);
            font-weight: 600;
        }

        /* Main content area */
        .main-content { flex-grow: 1; display: flex; flex-direction: column; overflow-y: auto; }
        .topbar {
            background-color: var(--admin-white);
            padding: 0.875rem 1.75rem;
            border-bottom: 1px solid var(--admin-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9375rem;
            font-weight: 500;
        }
        .user-info { font-weight: 600; display: flex; align-items: center; gap: 1rem; }
        .btn-logout {
            background-color: var(--admin-danger);
            color: #FFF;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: var(--admin-radius);
            cursor: pointer;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 600;
            font-family: inherit;
            transition: background 0.2s;
        }
        .btn-logout:hover { background-color: #B91C1C; }
        .content { padding: 1.75rem; }
        .content h1 {
            margin-top: 0;
            font-size: 1.75rem;
            color: var(--admin-navy);
            font-weight: 700;
            letter-spacing: -0.02em;
        }
        body.dark-mode .content h1 { color: #F8FAFC; }

        .alert-success {
            background: #ECFDF5;
            color: #065F46;
            padding: 0.875rem 1.25rem;
            margin-bottom: 1.25rem;
            border-radius: var(--admin-radius);
            border: 1px solid #A7F3D0;
            font-size: 0.9375rem;
        }

        /* Shared admin component classes */
        .admin-card {
            background: var(--admin-white);
            padding: 1.25rem;
            border-radius: var(--admin-radius);
            border: 1px solid var(--admin-border);
        }
        .admin-btn-primary {
            background: var(--admin-accent);
            color: #fff;
            border: 1px solid var(--admin-accent);
            padding: 0.5rem 1rem;
            border-radius: var(--admin-radius);
            cursor: pointer;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 600;
            font-family: inherit;
            display: inline-block;
            transition: background 0.2s;
        }
        .admin-btn-primary:hover { background: var(--admin-accent-dark); }
        .admin-btn-success {
            background: var(--admin-success);
            color: #fff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: var(--admin-radius);
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 600;
            font-family: inherit;
            transition: background 0.2s;
        }
        .admin-btn-success:hover { background: #047857; }
        .admin-link { color: var(--admin-accent); text-decoration: none; font-weight: 500; }
        .admin-link:hover { color: var(--admin-accent-dark); }
        .admin-text-muted { color: var(--admin-muted); }
    </style>
    @stack('styles')
    <script>
        if(localStorage.getItem('admin_theme') === 'dark') document.documentElement.classList.add('dark-mode');
        if(localStorage.getItem('admin_sidebar') === 'compact') document.documentElement.classList.add('compact-sidebar');
    </script>
</head>
<body onload="document.body.className = document.documentElement.className;">
    <div class="sidebar">
        <div class="sidebar-header">MediCart Admin</div>
        <ul class="sidebar-nav">
            <li><a href="{{ route('admin.dashboard') }}" @class(['active' => request()->routeIs('admin.dashboard')])>Dashboard</a></li>
            <li><a href="{{ route('admin.categories.index') }}" @class(['active' => request()->routeIs('admin.categories.*')])>Categories</a></li>
            <li><a href="{{ route('admin.brands.index') }}" @class(['active' => request()->routeIs('admin.brands.*')])>Brands</a></li>
            <li><a href="{{ route('admin.products.index') }}" @class(['active' => request()->routeIs('admin.products.*')])>Products</a></li>
            <li><a href="{{ route('admin.inventory.index') }}" @class(['active' => request()->routeIs('admin.inventory.*')])>Inventory</a></li>
            <li><a href="{{ route('admin.orders.index') }}" @class(['active' => request()->routeIs('admin.orders.*')])>Orders</a></li>
            <li><a href="{{ route('admin.customers.index') }}" @class(['active' => request()->routeIs('admin.customers.*')])>Customers</a></li>
            <li><a href="{{ route('admin.contacts.index') }}" @class(['active' => request()->routeIs('admin.contacts.*')])>Messages</a></li>
            <li><a href="{{ route('admin.settings.index') }}" @class(['active' => request()->routeIs('admin.settings.*')])>Settings</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="topbar">
            <div>Welcome to Admin Panel</div>
            <div class="user-info">
                <span>{{ Auth::user()->name ?? 'Admin' }}</span>
                <form action="{{ route('auth.logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-logout">Logout</button>
                </form>
            </div>
        </div>
        <div class="content">
            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif
            @yield('content')
        </div>
    </div>
    @stack('scripts')
</body>
</html>
