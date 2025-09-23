@extends('layouts.app')

@section('title', 'Dashboard - Aplikasi Toko')

@section('content')
    <!-- Welcome Card -->
    <div class="welcome-card">
        <h1 class="welcome-title">
            <i class="bi bi-emoji-smile text-warning me-2"></i>
            Selamat Datang, {{ Auth::user()->name }}
        </h1>
        <p class="welcome-subtitle">
            Selamat bekerja! Kelola toko Anda dengan mudah dan efisien.
        </p>
        <span class="role-badge">
            <i class="bi bi-shield-check me-1"></i>
            {{ ucfirst(Auth::user()->role) }}
        </span>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <!-- Semua admin bisa lihat total barang -->
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-box-seam"></i>
            </div>
            <h3 class="fw-bold text-dark mb-1">{{ $totalBarang ?? '150' }}</h3>
            <p class="text-muted mb-0">Total Barang</p>
        </div>

        <!-- Semua admin bisa lihat total penjualan -->
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-cart-check"></i>
            </div>
            <h3 class="fw-bold text-dark mb-1">{{ $totalPenjualan ?? '1,234' }}</h3>
            <p class="text-muted mb-0">Total Penjualan</p>
        </div>

        <!-- Semua admin bisa lihat total pendapatan -->
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <h3 class="fw-bold text-dark mb-1">Rp {{ number_format($totalPendapatan ?? 15500000) }}</h3>
            <p class="text-muted mb-0">Total Pendapatan</p>
        </div>

        <!-- Semua admin bisa lihat total users -->
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-people"></i>
            </div>
            <h3 class="fw-bold text-dark mb-1">{{ $totalUsers ?? '25' }}</h3>
            <p class="text-muted mb-0">Total Users</p>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function generateReport() {
            // Show loading toast
            showToast('Generating report...', 'info');

            // Simulate report generation
            setTimeout(() => {
                showToast('Report generated successfully!', 'success');
                // Here you would typically trigger a download or redirect
            }, 2000);
        }

        function viewProfile() {
            // Redirect to profile page or show modal
            showToast('Redirecting to profile...', 'info');
            // window.location.href = '/profile';
        }

        function showToast(message, type = 'info') {
            // Simple toast notification (you can replace with more sophisticated notification system)
            const toast = document.createElement('div');
            toast.className = `alert alert-${type} position-fixed`;
            toast.style.cssText = 'top: 100px; right: 20px; z-index: 9999; min-width: 300px;';
            toast.innerHTML = `
            <i class="bi bi-${type === 'success' ? 'check-circle' : type === 'info' ? 'info-circle' : 'exclamation-triangle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
        `;

            document.body.appendChild(toast);

            // Auto remove after 3 seconds
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 3000);
        }

        // Welcome animation
        document.addEventListener('DOMContentLoaded', function() {
            const welcomeCard = document.querySelector('.welcome-card');
            if (welcomeCard) {
                welcomeCard.style.opacity = '0';
                welcomeCard.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    welcomeCard.style.transition = 'all 0.6s ease';
                    welcomeCard.style.opacity = '1';
                    welcomeCard.style.transform = 'translateY(0)';
                }, 100);
            }

            // Animate stat cards
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 200 + (index * 100));
            });
        });
    </script>
@endpush
