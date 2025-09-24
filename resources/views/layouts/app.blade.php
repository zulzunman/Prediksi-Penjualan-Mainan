<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title', 'Aplikasi CRUD Barang')</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <style>
            :root {
                --sidebar-width: 280px;
                --header-height: 70px;
                --primary-color: #2563eb;
                --sidebar-bg: #1e293b;
                --sidebar-hover: #334155;
            }

            body {
                background-color: #f8fafc;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }

            /* Header Styles */
            .main-header {
                height: var(--header-height);
                background: linear-gradient(135deg, var(--primary-color) 0%, #3b82f6 100%);
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 1000;
            }

            .header-brand {
                font-size: 1.5rem;
                font-weight: 700;
                color: white !important;
                text-decoration: none;
            }

            .user-profile {
                background: rgba(255, 255, 255, 0.1);
                border-radius: 50px;
                padding: 8px 16px;
                color: white;
                border: 1px solid rgba(255, 255, 255, 0.2);
            }

            /* Sidebar Styles */
            .main-sidebar {
                width: var(--sidebar-width);
                height: calc(100vh - var(--header-height));
                background: var(--sidebar-bg);
                position: fixed;
                top: var(--header-height);
                left: 0;
                z-index: 999;
                transition: all 0.3s ease;
                box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            }

            .sidebar-menu {
                padding: 1.5rem 0;
            }

            .menu-header {
                color: #94a3b8;
                font-size: 0.75rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 1px;
                padding: 0 1.5rem;
                margin-bottom: 1rem;
            }

            .menu-item {
                margin: 0.25rem 1rem;
            }

            .menu-link {
                display: flex;
                align-items: center;
                padding: 0.75rem 1rem;
                color: #cbd5e1;
                text-decoration: none;
                border-radius: 8px;
                transition: all 0.3s ease;
                font-weight: 500;
            }

            .menu-link:hover {
                background: var(--sidebar-hover);
                color: white;
                transform: translateX(4px);
            }

            .menu-link.active {
                background: var(--primary-color);
                color: white;
                box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
            }

            .menu-link i {
                width: 20px;
                margin-right: 12px;
                font-size: 1.1rem;
            }

            .logout-section {
                position: absolute;
                bottom: 2rem;
                left: 1rem;
                right: 1rem;
            }

            .logout-btn {
                width: 100%;
                background: #dc2626;
                border: none;
                color: white;
                padding: 0.75rem;
                border-radius: 8px;
                font-weight: 600;
                transition: all 0.3s ease;
            }

            .logout-btn:hover {
                background: #b91c1c;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
            }

            /* Main Content */
            .main-content {
                margin-left: var(--sidebar-width);
                margin-top: var(--header-height);
                min-height: calc(100vh - var(--header-height) - 80px);
                padding: 2rem;
            }

            /* Footer */
            .main-footer {
                margin-left: var(--sidebar-width);
                background: white;
                padding: 1.5rem 2rem;
                border-top: 1px solid #e2e8f0;
                color: #64748b;
                text-align: center;
                margin-top: 2rem;
            }

            /* Welcome Card */
            .welcome-card {
                background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
                border: none;
                border-radius: 16px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
                padding: 2.5rem;
                margin-bottom: 2rem;
            }

            .welcome-title {
                font-size: 2rem;
                font-weight: 700;
                color: #1e293b;
                margin-bottom: 0.5rem;
            }

            .welcome-subtitle {
                color: #64748b;
                font-size: 1.1rem;
            }

            .role-badge {
                background: linear-gradient(135deg, var(--primary-color) 0%, #3b82f6 100%);
                color: white;
                padding: 0.5rem 1rem;
                border-radius: 50px;
                font-weight: 600;
                display: inline-block;
                margin-top: 1rem;
            }

            /* Stats Cards */
            .stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 1.5rem;
                margin-top: 2rem;
            }

            .stat-card {
                background: white;
                border-radius: 12px;
                padding: 1.5rem;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
                border-left: 4px solid var(--primary-color);
                transition: all 0.3s ease;
            }

            .stat-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            }

            .stat-icon {
                width: 48px;
                height: 48px;
                background: linear-gradient(135deg, var(--primary-color) 0%, #3b82f6 100%);
                color: white;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.5rem;
                margin-bottom: 1rem;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .main-sidebar {
                    transform: translateX(-100%);
                }

                .main-sidebar.show {
                    transform: translateX(0);
                }

                .main-content,
                .main-footer {
                    margin-left: 0;
                }

                .sidebar-toggle {
                    display: block !important;
                }
            }

            .sidebar-toggle {
                display: none;
                background: none;
                border: none;
                color: white;
                font-size: 1.5rem;
            }
        </style>
        @stack('styles')
    </head>

    <body>
        <!-- Header -->
        <header class="main-header">
            <div class="container-fluid h-100">
                <div class="d-flex align-items-center justify-content-between h-100">
                    <div class="d-flex align-items-center">
                        <button class="sidebar-toggle me-3" onclick="toggleSidebar()">
                            <i class="bi bi-list"></i>
                        </button>
                        <a href="{{ route('dashboard') }}" class="header-brand">
                            <i class="bi bi-shop me-2"></i>
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
                <div class="menu-header">Navigation</div>

                <div class="menu-item">
                    <a href="{{ route('dashboard') }}"
                        class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </div>

                <!-- Semua admin bisa akses barang -->
                <div class="menu-item">
                    <a href="{{ route('barang.index') }}"
                        class="menu-link {{ request()->is('barang*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam"></i>
                        <span>Barang</span>
                    </a>
                </div>

                <!-- Semua admin bisa akses penjualan -->
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

                {{-- Bisa ditambahkan menu lain sesuai kebutuhan --}}
                {{-- <div class="menu-item">
                    <a href="#" class="menu-link">
                        <i class="bi bi-people"></i>
                        <span>Users</span>
                    </a>
                </div>

                <div class="menu-item">
                    <a href="#" class="menu-link">
                        <i class="bi bi-bar-chart"></i>
                        <span>Reports</span>
                    </a>
                </div>

                <div class="menu-item">
                    <a href="#" class="menu-link">
                        <i class="bi bi-gear"></i>
                        <span>Settings</span>
                    </a>
                </div> --}}
            </div>

            @auth
                <div class="logout-section">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="logout-btn">
                            <i class="bi bi-box-arrow-right me-2"></i>
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
                    © {{ date('Y') }} Aplikasi Toko. Made with
                    <i class="bi bi-heart-fill text-danger"></i>
                    by Your Team
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
