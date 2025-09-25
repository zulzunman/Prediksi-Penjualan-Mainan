@extends('layouts.app')

@section('title', 'Dashboard - Aplikasi Toko')

@section('content')
    <!-- Welcome Section -->
    <div class="welcome-card">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h1 class="welcome-title">
                    Halo, {{ Auth::user()->name }}! 👋
                </h1>
                <p class="welcome-subtitle">
                    Semoga harimu menyenangkan. Mari kelola toko dengan efisien hari ini.
                </p>
                <span class="role-badge">
                    <i class="bi bi-shield-check me-1"></i>
                    {{ ucfirst(Auth::user()->role) }}
                </span>
            </div>
            <div class="d-none d-md-block">
                <i class="bi bi-calendar-check" style="font-size: 3rem; color: var(--primary-color); opacity: 0.1;"></i>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div class="text-end">
                    <p class="stat-value">{{ $totalBarang ?? '150' }}</p>
                    <p class="stat-label">Total Barang</p>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="bi bi-cart-check"></i>
                </div>
                <div class="text-end">
                    <p class="stat-value">{{ $totalPenjualan ?? '1,234' }}</p>
                    <p class="stat-label">Total Penjualan</p>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="text-end">
                    <p class="stat-value">Rp {{ number_format($totalPendapatan ?? 15500000) }}</p>
                    <p class="stat-label">Total Pendapatan</p>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="bi bi-people"></i>
                </div>
                <div class="text-end">
                    <p class="stat-value">{{ $totalUsers ?? '25' }}</p>
                    <p class="stat-label">Total Users</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-lightning-charge text-warning me-2"></i>
                        Aksi Cepat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="{{ route('barang.create') }}" class="btn btn-outline-primary w-100 py-3">
                                <i class="bi bi-plus-circle me-2"></i>
                                Tambah Barang
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('penjualan.create') }}" class="btn btn-outline-success w-100 py-3">
                                <i class="bi bi-cart-plus me-2"></i>
                                Input Penjualan
                            </a>
                        </div>
                        <div class="col-md-6">
                            <button onclick="generateReport()" class="btn btn-outline-info w-100 py-3">
                                <i class="bi bi-file-earmark-text me-2"></i>
                                Generate Laporan
                            </button>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('prediksi.index') }}" class="btn btn-outline-secondary w-100 py-3">
                                <i class="bi bi-graph-up me-2"></i>
                                Lihat Prediksi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clock-history text-info me-2"></i>
                        Aktivitas Terbaru
                    </h5>
                </div>
                <div class="card-body">
                    <div class="activity-item mb-3">
                        <div class="d-flex align-items-center">
                            <div class="activity-icon bg-success bg-opacity-10 text-success rounded-circle p-2 me-3">
                                <i class="bi bi-plus-circle"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-0 fw-medium">Barang baru ditambahkan</p>
                                <small class="text-muted">2 jam yang lalu</small>
                            </div>
                        </div>
                    </div>

                    <div class="activity-item mb-3">
                        <div class="d-flex align-items-center">
                            <div class="activity-icon bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3">
                                <i class="bi bi-cart-check"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-0 fw-medium">Penjualan berhasil</p>
                                <small class="text-muted">3 jam yang lalu</small>
                            </div>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="d-flex align-items-center">
                            <div class="activity-icon bg-warning bg-opacity-10 text-warning rounded-circle p-2 me-3">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-0 fw-medium">Stok hampir habis</p>
                                <small class="text-muted">5 jam yang lalu</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function generateReport() {
            // Show loading notification
            showNotification('Sedang memproses laporan...', 'info');

            // Simulate report generation
            setTimeout(() => {
                showNotification('Laporan berhasil dibuat!', 'success');
            }, 2000);
        }

        function showNotification(message, type = 'info') {
            const colors = {
                success: '#22c55e',
                info: '#3b82f6',
                warning: '#f59e0b',
                error: '#ef4444'
            };

            const icons = {
                success: 'check-circle',
                info: 'info-circle',
                warning: 'exclamation-triangle',
                error: 'x-circle'
            };

            const notification = document.createElement('div');
            notification.className = 'notification';
            notification.style.cssText = `
                position: fixed;
                top: 80px;
                right: 20px;
                z-index: 9999;
                background: white;
                color: var(--text-primary);
                padding: 1rem 1.5rem;
                border-radius: 8px;
                border-left: 4px solid ${colors[type]};
                box-shadow: var(--shadow-lg);
                min-width: 300px;
                max-width: 400px;
                transform: translateX(100%);
                transition: all 0.3s ease;
            `;

            notification.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="bi bi-${icons[type]} me-2" style="color: ${colors[type]}"></i>
                    <span class="flex-grow-1">${message}</span>
                    <button type="button" class="btn-close btn-sm ms-2" onclick="this.closest('.notification').remove()"></button>
                </div>
            `;

            document.body.appendChild(notification);

            // Animate in
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 100);

            // Auto remove after 4 seconds
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.style.transform = 'translateX(100%)';
                    setTimeout(() => notification.remove(), 300);
                }
            }, 4000);
        }

        // Add smooth animations on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Animate welcome card
            const welcomeCard = document.querySelector('.welcome-card');
            if (welcomeCard) {
                welcomeCard.style.opacity = '0';
                welcomeCard.style.transform = 'translateY(10px)';

                setTimeout(() => {
                    welcomeCard.style.transition = 'all 0.4s ease';
                    welcomeCard.style.opacity = '1';
                    welcomeCard.style.transform = 'translateY(0)';
                }, 100);
            }

            // Animate stat cards
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(10px)';

                setTimeout(() => {
                    card.style.transition = 'all 0.4s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 200 + (index * 100));
            });

            // Animate other cards
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(10px)';

                setTimeout(() => {
                    card.style.transition = 'all 0.4s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 600 + (index * 150));
            });
        });
    </script>

    <style>
        .activity-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
        }

        .btn-outline-primary:hover,
        .btn-outline-success:hover,
        .btn-outline-info:hover,
        .btn-outline-secondary:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .card {
            border-radius: 10px;
        }

        .card-header {
            border-radius: 10px 10px 0 0 !important;
        }
    </style>
@endpush
