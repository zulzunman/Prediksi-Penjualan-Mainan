<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title', 'Aplikasi CRUD Barang')</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Tambahkan ini di head section layout Anda (layouts/app.blade.php) -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <!-- Atau jika menggunakan CDN alternatif -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <style>
            :root {
                --sidebar-width: 260px;
                --header-height: 65px;
                --primary-color: #6366f1;
                --secondary-color: #8b5cf6;
                --sidebar-bg: #ffffff;
                --sidebar-border: #e5e7eb;
                --sidebar-hover: #f3f4f6;
                --sidebar-active: #eef2ff;
                --text-primary: #1f2937;
                --text-secondary: #6b7280;
                --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
                --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
                --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            }

            * {
                box-sizing: border-box;
            }

            body {
                background-color: #f9fafb;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
                margin: 0;
                padding: 0;
            }

            /* Header Styles */
            .main-header {
                height: var(--header-height);
                background: white;
                border-bottom: 1px solid var(--sidebar-border);
                box-shadow: var(--shadow-sm);
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 1000;
            }

            .header-brand {
                font-size: 1.25rem;
                font-weight: 600;
                color: var(--text-primary) !important;
                text-decoration: none;
                display: flex;
                align-items: center;
            }

            .header-brand i {
                color: var(--primary-color);
                margin-right: 8px;
            }

            .user-profile {
                background: var(--sidebar-hover);
                border-radius: 8px;
                padding: 8px 12px;
                color: var(--text-secondary);
                border: 1px solid var(--sidebar-border);
                font-size: 0.875rem;
                transition: all 0.2s ease;
            }

            .user-profile:hover {
                background: #e5e7eb;
            }

            /* Sidebar Styles */
            .main-sidebar {
                width: var(--sidebar-width);
                height: calc(100vh - var(--header-height));
                background: var(--sidebar-bg);
                border-right: 1px solid var(--sidebar-border);
                position: fixed;
                top: var(--header-height);
                left: 0;
                z-index: 999;
                transition: all 0.3s ease;
                overflow-y: auto;
            }

            .sidebar-menu {
                padding: 1rem 0;
            }

            .menu-header {
                color: var(--text-secondary);
                font-size: 0.75rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                padding: 0 1rem;
                margin-bottom: 0.5rem;
            }

            .menu-item {
                margin: 2px 0.75rem;
            }

            .menu-link {
                display: flex;
                align-items: center;
                padding: 0.6rem 0.75rem;
                color: var(--text-secondary);
                text-decoration: none;
                border-radius: 6px;
                transition: all 0.2s ease;
                font-weight: 500;
                font-size: 0.875rem;
            }

            .menu-link:hover {
                background: var(--sidebar-hover);
                color: var(--text-primary);
            }

            .menu-link.active {
                background: var(--sidebar-active);
                color: var(--primary-color);
                font-weight: 600;
            }

            .menu-link i {
                width: 18px;
                margin-right: 10px;
                font-size: 1rem;
                text-align: center;
            }

            .logout-section {
                position: absolute;
                bottom: 1rem;
                left: 0.75rem;
                right: 0.75rem;
            }

            .logout-btn {
                width: 100%;
                background: #ef4444;
                border: none;
                color: white;
                padding: 0.6rem;
                border-radius: 6px;
                font-weight: 500;
                font-size: 0.875rem;
                transition: all 0.2s ease;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .logout-btn:hover {
                background: #dc2626;
            }

            .logout-btn i {
                margin-right: 6px;
            }

            /* Main Content */
            .main-content {
                margin-left: var(--sidebar-width);
                margin-top: var(--header-height);
                min-height: calc(100vh - var(--header-height));
                padding: 1.5rem;
            }

            /* Footer */
            .main-footer {
                margin-left: var(--sidebar-width);
                background: white;
                padding: 1rem 1.5rem;
                border-top: 1px solid var(--sidebar-border);
                color: var(--text-secondary);
                text-align: center;
                font-size: 0.875rem;
            }

            /* Welcome Card */
            .welcome-card {
                background: white;
                border: 1px solid var(--sidebar-border);
                border-radius: 12px;
                box-shadow: var(--shadow-sm);
                padding: 2rem;
                margin-bottom: 1.5rem;
            }

            .welcome-title {
                font-size: 1.75rem;
                font-weight: 700;
                color: var(--text-primary);
                margin-bottom: 0.5rem;
            }

            .welcome-subtitle {
                color: var(--text-secondary);
                font-size: 1rem;
                margin-bottom: 1rem;
            }

            .role-badge {
                background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
                color: white;
                padding: 0.4rem 0.8rem;
                border-radius: 20px;
                font-weight: 500;
                font-size: 0.8rem;
                display: inline-flex;
                align-items: center;
            }

            /* Stats Cards */
            .stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
                gap: 1rem;
            }

            .stat-card {
                background: white;
                border: 1px solid var(--sidebar-border);
                border-radius: 10px;
                padding: 1.25rem;
                box-shadow: var(--shadow-sm);
                transition: all 0.2s ease;
            }

            .stat-card:hover {
                transform: translateY(-1px);
                box-shadow: var(--shadow-md);
            }

            .stat-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 1rem;
            }

            .stat-icon {
                width: 40px;
                height: 40px;
                background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
                color: white;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.2rem;
            }

            .stat-value {
                font-size: 1.5rem;
                font-weight: 700;
                color: var(--text-primary);
                margin: 0;
            }

            .stat-label {
                color: var(--text-secondary);
                font-size: 0.875rem;
                margin: 0;
                margin-top: 0.25rem;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .main-sidebar {
                    transform: translateX(-100%);
                }

                .main-sidebar.show {
                    transform: translateX(0);
                    box-shadow: var(--shadow-lg);
                }

                .main-content,
                .main-footer {
                    margin-left: 0;
                }

                .sidebar-toggle {
                    display: flex !important;
                }

                .stats-grid {
                    grid-template-columns: 1fr;
                }
            }

            .sidebar-toggle {
                display: none;
                background: none;
                border: none;
                color: var(--text-secondary);
                font-size: 1.25rem;
                align-items: center;
                justify-content: center;
                padding: 0.5rem;
                border-radius: 4px;
                transition: all 0.2s ease;
            }

            .sidebar-toggle:hover {
                background: var(--sidebar-hover);
                color: var(--text-primary);
            }

            /* Scrollbar styling */
            .main-sidebar::-webkit-scrollbar {
                width: 4px;
            }

            .main-sidebar::-webkit-scrollbar-track {
                background: transparent;
            }

            .main-sidebar::-webkit-scrollbar-thumb {
                background: #d1d5db;
                border-radius: 2px;
            }

            .main-sidebar::-webkit-scrollbar-thumb:hover {
                background: #9ca3af;
            }
        </style>
        @stack('styles')
    </head>
    @stack('scripts')

    <body>
        <!-- Header -->
        <header class="main-header">
            <div class="container-fluid h-100">
                <div class="d-flex align-items-center justify-content-between h-100 px-3">
                    <div class="d-flex align-items-center">
                        <button class="sidebar-toggle me-3" onclick="toggleSidebar()">
                            <i class="bi bi-list"></i>
                        </button>
                        <a href="{{ route('dashboard') }}" class="header-brand">
                            <i class="bi bi-shop"></i>
                            Aplikasi Toko
                        </a>
                    </div>

                    @auth
                        <div class="user-profile">
                            <i class="bi bi-person-circle me-2"></i>
                            <span>{{ Auth::user()->name }}</span>
                        </div>
                    @endauth
                </div>
            </div>
        </header>

        <!-- Sidebar -->
        <aside class="main-sidebar" id="sidebar">
            <div class="sidebar-menu">
                <div class="menu-header">Menu Utama</div>

                <div class="menu-item">
                    <a href="{{ route('dashboard') }}"
                        class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-house"></i>
                        <span>Dashboard</span>
                    </a>
                </div>

                <div class="menu-item">
                    <a href="{{ route('barang.index') }}"
                        class="menu-link {{ request()->is('barang*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam"></i>
                        <span>Data Barang</span>
                    </a>
                </div>

                <div class="menu-item">
                    <a href="{{ route('penjualan.index') }}"
                        class="menu-link {{ request()->is('penjualan*') ? 'active' : '' }}">
                        <i class="bi bi-cart3"></i>
                        <span>Penjualan</span>
                    </a>
                </div>

                <div class="menu-item">
                    <a href="{{ route('prediksi.index') }}"
                        class="menu-link {{ request()->is('prediksi*') ? 'active' : '' }}">
                        <i class="bi bi-graph-up"></i>
                        <span>Prediksi</span>
                    </a>
                </div>
            </div>

            @auth
                <div class="logout-section">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="logout-btn">
                            <i class="bi bi-box-arrow-right"></i>
                            Logout
                        </button>
                    </form>
                </div>
            @endauth
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="main-footer">
            <div class="container-fluid">
                <p class="mb-0">
                    © {{ date('Y') }} Aplikasi Toko. Dibuat dengan
                    <i class="bi bi-heart-fill text-danger"></i>
                    oleh Tim Developer
                </p>
            </div>
        </footer>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            function toggleSidebar() {
                const sidebar = document.getElementById('sidebar');
                sidebar.classList.toggle('show');
            }

            // Auto-hide mobile sidebar when clicking outside
            document.addEventListener('click', function(e) {
                const sidebar = document.getElementById('sidebar');
                const toggle = document.querySelector('.sidebar-toggle');

                if (window.innerWidth <= 768 &&
                    sidebar &&
                    !sidebar.contains(e.target) &&
                    !toggle.contains(e.target)) {
                    sidebar.classList.remove('show');
                }
            });

            // Close sidebar when window is resized to desktop
            window.addEventListener('resize', function() {
                const sidebar = document.getElementById('sidebar');
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('show');
                }
            });
        </script>

        @stack('scripts')
    </body>

</html>
