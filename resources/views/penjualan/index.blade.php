{{-- resources/views/penjualan/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Penjualan - Aplikasi Toko')

@section('content')
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">
                <i class="bi bi-graph-up text-success me-2"></i>
                Data Penjualan
            </h1>
            <p class="text-muted mb-0">Kelola dan pantau data penjualan toko Anda</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#importPenjualanModal">
                <i class="bi bi-cloud-upload me-2"></i>
                Import Excel
            </button>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="bi bi-plus-circle me-2"></i>
                Tambah Penjualan
            </button>
        </div>
    </div>

    {{-- Alerts --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tabs Navigation --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <ul class="nav nav-pills nav-fill" id="penjualanTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $activeTab === 'data' ? 'active' : '' }}"
                        href="{{ route('penjualan.index', ['tab' => 'data', 'bulan' => $bulan, 'tahun' => $tahun]) }}">
                        <i class="bi bi-table me-2"></i>
                        Data Penjualan
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $activeTab === 'ringkasan' ? 'active' : '' }}"
                        href="{{ route('penjualan.index', ['tab' => 'ringkasan', 'bulan' => $bulan, 'tahun' => $tahun]) }}">
                        <i class="bi bi-pie-chart me-2"></i>
                        Ringkasan Penjualan
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body p-0">
            <div class="tab-content" id="penjualanTabsContent">
                {{-- Tab Data Penjualan --}}
                <div class="tab-pane fade {{ $activeTab === 'data' ? 'show active' : '' }}" id="data-tab-pane"
                    role="tabpanel">

                    {{-- Basic Filter for Data Tab --}}
                    <div class="p-3 border-bottom">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-6">
                                <form method="GET" class="row g-3 align-items-end">
                                    <input type="hidden" name="tab" value="data">
                                    <input type="hidden" name="per_page" value="{{ $perPage }}">

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Bulan</label>
                                        <select name="bulan" class="form-select">
                                            <option value="all" {{ $bulan === 'all' ? 'selected' : '' }}>Semua Bulan
                                            </option>
                                            @foreach ($monthNames as $monthNum => $monthName)
                                                <option value="{{ $monthNum }}"
                                                    {{ $bulan == $monthNum ? 'selected' : '' }}>
                                                    {{ $monthName }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Tahun</label>
                                        <select name="tahun" class="form-select">
                                            @foreach ($availableYears as $year)
                                                <option value="{{ $year->year }}"
                                                    {{ $tahun == $year->year ? 'selected' : '' }}>
                                                    {{ $year->year }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="bi bi-search"></i> Filter
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <div class="col-md-6 text-end">
                                <div class="d-flex justify-content-end gap-2 align-items-end">
                                    {{-- Per Page Selector --}}
                                    <div class="me-3">
                                        <label class="form-label fw-semibold small mb-1">Tampilkan</label>
                                        <form method="GET" class="d-inline">
                                            <input type="hidden" name="tab" value="data">
                                            <input type="hidden" name="bulan" value="{{ $bulan }}">
                                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                                            <select name="per_page" class="form-select form-select-sm"
                                                onchange="this.form.submit()" style="width: 80px;">
                                                @foreach ($perPageOptions as $option)
                                                    <option value="{{ $option }}"
                                                        {{ $perPage == $option ? 'selected' : '' }}>
                                                        {{ $option }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </form>
                                    </div>

                                    <div>
                                        <a href="{{ route('penjualan.index', ['tab' => 'data']) }}"
                                            class="btn btn-outline-secondary">
                                            <i class="bi bi-arrow-clockwise"></i> Reset
                                        </a>
                                        <a href="{{ route('penjualan.template') }}" class="btn btn-outline-success"
                                            target="_blank" title="Download Template Excel">
                                            <i class="bi bi-download"></i> Template
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Pagination Info --}}
                    @if ($penjualan->total() > 0)
                        <div class="px-3 py-2 bg-light border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    Menampilkan {{ $penjualan->firstItem() }} - {{ $penjualan->lastItem() }}
                                    dari {{ $penjualan->total() }} total data
                                </small>
                                <small class="text-muted">
                                    <i class="bi bi-calendar me-1"></i>
                                    Filter:
                                    @if (isset($bulan) && $bulan === 'all')
                                        Semua Bulan
                                        @if (!empty($tahun))
                                            {{ $tahun }}
                                        @endif
                                    @else
                                        {{ $monthNames[$bulan] ?? '—' }} {{ $tahun ?? '' }}
                                    @endif
                                </small>
                            </div>
                        </div>
                    @endif

                    {{-- Data Table --}}
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">No</th>
                                    <th class="border-0">Nama Barang</th>
                                    <th class="border-0">Jumlah</th>
                                    <th class="border-0">Harga Satuan</th>
                                    <th class="border-0">Total</th>
                                    <th class="border-0">Tanggal</th>
                                    <th class="border-0">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($penjualan as $index => $p)
                                    <tr>
                                        <td>{{ $penjualan->firstItem() + $index }}</td>
                                        <td>{{ $p->nama_barang }}</td>
                                        <td>{{ $p->jumlah_penjualan }} unit</td>
                                        <td>Rp {{ number_format($p->harga_satuan) }}</td>
                                        <td class="fw-semibold text-success">Rp {{ number_format($p->total_harga) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#editPenjualanModal{{ $p->id }}">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-outline-danger btn-sm"
                                                    onclick="deleteItem({{ $p->id }}, '{{ $p->nama_barang }}')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="bi bi-inbox display-4 text-muted"></i>
                                            <p class="text-muted mt-2">Tidak ada data penjualan</p>
                                            <div class="mt-3">
                                                <button class="btn btn-primary me-2" data-bs-toggle="modal"
                                                    data-bs-target="#createPenjualanModal">
                                                    <i class="bi bi-plus-circle me-2"></i>
                                                    Tambah Penjualan
                                                </button>
                                                <button class="btn btn-outline-primary" data-bs-toggle="modal"
                                                    data-bs-target="#importPenjualanModal">
                                                    <i class="bi bi-cloud-upload me-2"></i>
                                                    Import Excel
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination for Data Tab --}}
                    @if ($penjualan->hasPages())
                        <div class="px-3 py-3 border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">
                                        Halaman {{ $penjualan->currentPage() }} dari {{ $penjualan->lastPage() }}
                                    </small>
                                </div>
                                <div>
                                    {{ $penjualan->appends(request()->query())->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Tab Ringkasan Penjualan --}}
                <div class="tab-pane fade {{ $activeTab === 'ringkasan' ? 'show active' : '' }}" id="ringkasan-tab-pane"
                    role="tabpanel">

                    {{-- Enhanced Filter Section --}}
                    <div class="p-3 border-bottom">
                        <form method="GET" id="filterForm" class="row g-3">
                            <input type="hidden" name="tab" value="ringkasan">
                            <input type="hidden" name="per_page" value="{{ $perPage }}">

                            {{-- Filter Barang --}}
                            <div class="col-md-3">
                                <label for="barang_filter" class="form-label fw-semibold">
                                    <i class="bi bi-box me-1"></i>Pilih Barang
                                </label>
                                <select name="barang_filter" id="barang_filter" class="form-select">
                                    <option value="">Semua Barang</option>
                                    @foreach ($barangFilter as $barangName)
                                        <option value="{{ $barangName }}"
                                            {{ $barang_filter === $barangName ? 'selected' : '' }}>
                                            {{ $barangName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Filter Bulan --}}
                            <div class="col-md-3">
                                <label for="bulan" class="form-label fw-semibold">
                                    <i class="bi bi-calendar-month me-1"></i>Bulan
                                </label>
                                <select name="bulan" id="bulan" class="form-select">
                                    <option value="all" {{ $bulan === 'all' ? 'selected' : '' }}>Semua Bulan</option>
                                    @foreach ($monthNames as $monthNum => $monthName)
                                        <option value="{{ $monthNum }}" {{ $bulan == $monthNum ? 'selected' : '' }}>
                                            {{ $monthName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Filter Tahun --}}
                            <div class="col-md-3">
                                <label for="tahun" class="form-label fw-semibold">
                                    <i class="bi bi-calendar-year me-1"></i>Tahun
                                </label>
                                <select name="tahun" id="tahun" class="form-select">
                                    @foreach ($availableYears as $year)
                                        <option value="{{ $year->year }}"
                                            {{ $tahun == $year->year ? 'selected' : '' }}>
                                            {{ $year->year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="col-md-3 d-flex align-items-end">
                                <div class="btn-group w-100" role="group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-search me-1"></i>
                                        Filter
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" id="resetFilter">
                                        <i class="bi bi-arrow-clockwise me-1"></i>
                                        Reset
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- Per Page and Filter Info for Ringkasan --}}
                    <div class="p-2 bg-light border-bottom">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-info-circle text-primary me-2"></i>
                                <small class="text-muted">
                                    Menampilkan ringkasan untuk:
                                    <span class="fw-semibold text-dark" id="currentFilter">
                                        {{ $barang_filter ?: 'Semua Barang' }} -
                                        {{ $bulan === 'all' ? 'Semua Bulan' : $monthNames[(int) $bulan] ?? '-' }}
                                        {{ $tahun }}
                                    </span>
                                </small>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                {{-- Per Page Selector for Ringkasan --}}
                                <div class="d-flex align-items-center">
                                    <small class="text-muted me-2">Tampilkan:</small>
                                    <form method="GET" class="d-inline">
                                        <input type="hidden" name="tab" value="ringkasan">
                                        <input type="hidden" name="bulan" value="{{ $bulan }}">
                                        <input type="hidden" name="tahun" value="{{ $tahun }}">
                                        <input type="hidden" name="barang_filter" value="{{ $barang_filter }}">
                                        <select name="per_page" class="form-select form-select-sm"
                                            onchange="this.form.submit()" style="width: 70px;">
                                            @foreach ($perPageOptions as $option)
                                                <option value="{{ $option }}"
                                                    {{ $perPage == $option ? 'selected' : '' }}>
                                                    {{ $option }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </div>
                                <div class="d-flex align-items-center">
                                    <small class="text-muted me-2">Total data:</small>
                                    <span class="badge bg-primary-subtle text-primary">
                                        {{ $ringkasanPenjualan->total() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Pagination Info for Ringkasan --}}
                    @if ($ringkasanPenjualan->total() > 0)
                        <div class="px-3 py-2 bg-light border-bottom">
                            <small class="text-muted">
                                Menampilkan {{ $ringkasanPenjualan->firstItem() }} - {{ $ringkasanPenjualan->lastItem() }}
                                dari {{ $ringkasanPenjualan->total() }} jenis barang
                            </small>
                        </div>
                    @endif

                    {{-- Ringkasan Table --}}
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">No</th>
                                    <th class="border-0">Nama Barang</th>
                                    <th class="border-0 text-center">Total Terjual</th>
                                    <th class="border-0 text-end">Total Pendapatan</th>
                                    <th class="border-0 text-end">Harga Rata-rata</th>
                                    <th class="border-0 text-center">Jumlah Transaksi</th>
                                    <th class="border-0">Transaksi Terakhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ringkasanPenjualan as $index => $r)
                                    <tr>
                                        <td class="fw-medium">{{ $ringkasanPenjualan->firstItem() + $index }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                                    <i class="bi bi-box text-primary"></i>
                                                </div>
                                                <div class="fw-semibold">{{ $r->nama_barang }}</div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success-subtle text-success fs-6">
                                                {{ number_format($r->total_terjual) }} unit
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <div class="fw-semibold text-success">
                                                Rp {{ number_format($r->total_pendapatan) }}
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <div class="text-muted">
                                                Rp {{ number_format($r->harga_rata_rata) }}
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info-subtle text-info">
                                                {{ $r->jumlah_transaksi }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="text-muted">
                                                {{ \Carbon\Carbon::parse($r->transaksi_terakhir)->format('d M Y') }}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="bi bi-pie-chart display-4 text-muted"></i>
                                            <p class="text-muted mt-2">Tidak ada data ringkasan</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination for Ringkasan Tab --}}
                    @if ($ringkasanPenjualan->hasPages())
                        <div class="px-3 py-3 border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">
                                        Halaman {{ $ringkasanPenjualan->currentPage() }} dari
                                        {{ $ringkasanPenjualan->lastPage() }}
                                    </small>
                                </div>
                                <div>
                                    {{ $ringkasanPenjualan->appends(request()->query())->onEachSide(2)->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Summary Footer --}}
                    @if ($ringkasanPenjualan->count() > 0)
                        <div class="p-3 bg-light border-top">
                            <div class="row">
                                <div class="col-md-8">
                                    <small class="text-muted">
                                        Total {{ $ringkasanPenjualan->total() }} jenis barang dari
                                        {{ $ringkasanPenjualan->count() }} yang ditampilkan
                                    </small>
                                </div>
                                <div class="col-md-4 text-end">
                                    <div class="d-flex align-items-center justify-content-end">
                                        <small class="text-muted me-2">Total Halaman Ini:</small>
                                        <span class="fw-semibold text-success fs-6">
                                            Rp {{ number_format($ringkasanPenjualan->sum('total_pendapatan')) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Include Modals --}}
    @include('penjualan.create')
    @include('penjualan.import')

    @foreach ($penjualan as $p)
        @include('penjualan.edit', ['penjualan' => $p])
    @endforeach
@endsection

@push('styles')
    <style>
        .stat-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .nav-pills .nav-link {
            border-radius: 0;
            border-bottom: 3px solid transparent;
            background: none !important;
            color: #6c757d;
        }

        .nav-pills .nav-link.active {
            border-bottom-color: #0d6efd;
            color: #0d6efd;
            background: rgba(13, 110, 253, 0.1) !important;
        }

        .border-primary.bg-primary-subtle {
            border-width: 2px !important;
        }

        .border-info.bg-info-subtle {
            border-width: 2px !important;
        }

        /* Custom pagination styling */
        .pagination {
            margin-bottom: 0;
        }

        .pagination .page-link {
            border: 1px solid #dee2e6;
            color: #6c757d;
            padding: 0.375rem 0.75rem;
        }

        .pagination .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white;
        }

        .pagination .page-link:hover {
            background-color: #e9ecef;
            border-color: #dee2e6;
            color: #0d6efd;
        }

        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            background-color: #fff;
            border-color: #dee2e6;
        }

        /* Responsive pagination */
        @media (max-width: 576px) {
            .pagination {
                font-size: 0.875rem;
            }

            .pagination .page-link {
                padding: 0.25rem 0.5rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Reset Filter Function
        document.addEventListener('DOMContentLoaded', function() {
            const resetBtn = document.getElementById('resetFilter');
            if (resetBtn) {
                resetBtn.addEventListener('click', function() {
                    const baseUrl = window.location.protocol + "//" + window.location.host + window.location
                        .pathname;
                    const defaultParams = new URLSearchParams({
                        'tab': 'ringkasan',
                        'bulan': '{{ date('m') }}',
                        'tahun': '{{ date('Y') }}',
                        'barang_filter': '',
                        'per_page': '{{ $perPage }}'
                    });
                    window.location.href = baseUrl + '?' + defaultParams.toString();
                });
            }

            // Highlight active filters
            const filters = ['barang_filter', 'bulan', 'tahun'];
            filters.forEach(filterId => {
                const element = document.getElementById(filterId);
                if (element && element.value && element.value !== '' && element.value !== 'all') {
                    element.classList.add('border-primary', 'bg-primary-subtle');
                } else if (filterId === 'bulan' && element && element.value === 'all') {
                    element.classList.add('border-info', 'bg-info-subtle');
                }
            });
        });

        // Delete function
        function deleteItem(id, name) {
            if (confirm(`Apakah Anda yakin ingin menghapus penjualan "${name}"?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/penjualan/${id}`;
                form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Auto-submit form when per_page changes
        function handlePerPageChange(element) {
            element.closest('form').submit();
        }
    </script>
@endpush
