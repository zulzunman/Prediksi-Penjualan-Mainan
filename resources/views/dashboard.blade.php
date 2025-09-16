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
        @if (Auth::user()->role == 'admin' || Auth::user()->role == 'pemilik')
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-box-seam"></i>
                </div>
                <h3 class="fw-bold text-dark mb-1">{{ $totalBarang ?? '150' }}</h3>
                <p class="text-muted mb-0">Total Barang</p>
            </div>
        @endif

        @if (Auth::user()->role == 'pemilik')
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-cart-check"></i>
                </div>
                <h3 class="fw-bold text-dark mb-1">{{ $totalPenjualan ?? '1,234' }}</h3>
                <p class="text-muted mb-0">Total Penjualan</p>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <h3 class="fw-bold text-dark mb-1">Rp {{ number_format($totalPendapatan ?? 15500000) }}</h3>
                <p class="text-muted mb-0">Total Pendapatan</p>
            </div>
        @endif

        @if (Auth::user()->role == 'admin')
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-people"></i>
                </div>
                <h3 class="fw-bold text-dark mb-1">{{ $totalUsers ?? '25' }}</h3>
                <p class="text-muted mb-0">Total Users</p>
            </div>
        @endif
    </div>
    {{-- 
    <!-- Action Cards -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning me-2"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if (Auth::user()->role == 'admin')
                            <a href="{{ route('barang.create') }}" class="btn btn-outline-primary">
                                <i class="bi bi-plus-circle me-2"></i>
                                Tambah Barang Baru
                            </a>
                        @endif

                        @if (Auth::user()->role == 'pemilik')
                            <a href="{{ route('penjualan.create') }}" class="btn btn-outline-success">
                                <i class="bi bi-cart-plus me-2"></i>
                                Input Penjualan
                            </a>
                        @endif

                        <button class="btn btn-outline-info" onclick="generateReport()">
                            <i class="bi bi-file-earmark-text me-2"></i>
                            Generate Report
                        </button>

                        <button class="btn btn-outline-warning" onclick="viewProfile()">
                            <i class="bi bi-person-gear me-2"></i>
                            Edit Profile
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-activity me-2"></i>
                        Recent Activity
                    </h5>
                </div>
                <div class="card-body">
                    @if (isset($recentActivities) && count($recentActivities) > 0)
                        @foreach ($recentActivities as $activity)
                            <div class="activity-item d-flex align-items-center mb-3">
                                <div class="activity-icon bg-{{ $activity['color'] ?? 'primary' }} text-white rounded-circle p-2 me-3"
                                    style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-{{ $activity['icon'] ?? 'activity' }}"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $activity['message'] }}</div>
                                    <small class="text-muted">{{ $activity['time'] }}</small>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <!-- Default activities for demo -->
                        <div class="activity-item d-flex align-items-center mb-3">
                            <div class="activity-icon bg-primary text-white rounded-circle p-2 me-3"
                                style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-box"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Login berhasil</div>
                                <small class="text-muted">Baru saja</small>
                            </div>
                        </div>

                        @if (Auth::user()->role == 'admin')
                            <div class="activity-item d-flex align-items-center mb-3">
                                <div class="activity-icon bg-success text-white rounded-circle p-2 me-3"
                                    style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-box-seam"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">Data barang diperbarui</div>
                                    <small class="text-muted">2 jam yang lalu</small>
                                </div>
                            </div>
                        @endif

                        @if (Auth::user()->role == 'pemilik')
                            <div class="activity-item d-flex align-items-center mb-3">
                                <div class="activity-icon bg-success text-white rounded-circle p-2 me-3"
                                    style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-cart-check"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">Penjualan baru Rp 2,500,000</div>
                                    <small class="text-muted">3 jam yang lalu</small>
                                </div>
                            </div>
                        @endif

                        <div class="activity-item d-flex align-items-center">
                            <div class="activity-icon bg-info text-white rounded-circle p-2 me-3"
                                style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-gear"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Sistem diperbarui</div>
                                <small class="text-muted">1 hari yang lalu</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div> --}}

    {{-- @if (Auth::user()->role == 'pemilik')
        <!-- Sales Chart (untuk pemilik) -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-graph-up me-2"></i>
                            Grafik Penjualan Bulanan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center py-5">
                            <i class="bi bi-graph-up display-1 text-muted"></i>
                            <p class="text-muted mt-3">Grafik penjualan akan ditampilkan di sini</p>
                            <small class="text-muted">Integrasi dengan Chart.js atau library grafik lainnya</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif --}}
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
