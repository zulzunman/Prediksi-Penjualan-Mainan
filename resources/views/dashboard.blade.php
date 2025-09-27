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
                    <i class="bi bi-box-seam"></i>
                </div>
                <div class="text-end">
                    <p class="stat-value">{{ $totalPrediksi ?? '150' }}</p>
                    <p class="stat-label">Total Prediksi</p>
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

        {{-- <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="text-end">
                    <p class="stat-value">Rp {{ number_format($totalPendapatan ?? 15500000) }}</p>
                    <p class="stat-label">Total Pendapatan</p>
                </div>
            </div>
        </div> --}}

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
