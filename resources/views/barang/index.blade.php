@extends('layouts.app')

@section('title', 'Daftar Barang - Aplikasi Toko')

@section('content')
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">
                <i class="bi bi-box-seam text-primary me-2"></i>
                Daftar Barang
            </h1>
            <p class="text-muted mb-0">Kelola data barang di toko Anda</p>
        </div>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBarangModal">
                <i class="bi bi-plus-circle me-2"></i>
                Tambah Barang
            </button>
        </div>
    </div>

    <!-- Success Alert -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Error Alert -->
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Terdapat kesalahan:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary text-white rounded me-3">
                            <i class="bi bi-box"></i>
                        </div>
                        <div>
                            <h6 class="card-title text-muted mb-1">Total Barang</h6>
                            <h4 class="mb-0">{{ $barang->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success text-white rounded me-3">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div>
                            <h6 class="card-title text-muted mb-1">Stok Tersedia</h6>
                            <h4 class="mb-0">{{ $barang->where('stok', '>', 0)->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning text-white rounded me-3">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                        <div>
                            <h6 class="card-title text-muted mb-1">Stok Habis</h6>
                            <h4 class="mb-0">{{ $barang->where('stok', '=', 0)->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-info text-white rounded me-3">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div>
                            <h6 class="card-title text-muted mb-1">Nilai Total</h6>
                            <h4 class="mb-0">Rp
                                {{ number_format($barang->sum(function ($item) {return $item->harga * $item->stok;})) }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" id="searchInput" class="form-control border-start-0"
                            placeholder="Cari nama barang...">
                    </div>
                </div>
                {{-- <div class="col-md-3">
                    <select id="statusFilter" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="available">Stok Tersedia</option>
                        <option value="empty">Stok Habis</option>
                        <option value="low">Stok Menipis (< 10)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="btn-group w-100" role="group">
                        <button type="button" class="btn btn-outline-secondary active" data-view="table">
                            <i class="bi bi-table me-1"></i>
                            Table
                        </button>
                        <button type="button" class="btn btn-outline-secondary" data-view="grid">
                            <i class="bi bi-grid-3x3 me-1"></i>
                            Grid
                        </button>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>

    <!-- Table View -->
    <div id="tableView" class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="bi bi-table me-2"></i>
                    Data Barang
                </h6>
                {{-- <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                        data-bs-toggle="dropdown">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="exportData('excel')">
                                <i class="bi bi-file-excel me-2"></i>Export Excel
                            </a></li>
                        <li><a class="dropdown-item" href="#" onclick="exportData('pdf')">
                                <i class="bi bi-file-pdf me-2"></i>Export PDF
                            </a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#" onclick="printTable()">
                                <i class="bi bi-printer me-2"></i>Print
                            </a></li>
                    </ul>
                </div> --}}
            </div>
        </div>
        <div class="card-body p-0">
            @if ($barang->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="barangTable">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 fw-semibold">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                    </div>
                                </th>
                                <th class="border-0 fw-semibold">No</th>
                                <th class="border-0 fw-semibold">
                                    <i class="bi bi-box me-1"></i>Nama Barang
                                </th>
                                <th class="border-0 fw-semibold">
                                    <i class="bi bi-stack me-1"></i>Stok
                                </th>
                                <th class="border-0 fw-semibold">
                                    <i class="bi bi-currency-dollar me-1"></i>Harga
                                </th>
                                <th class="border-0 fw-semibold">
                                    <i class="bi bi-calculator me-1"></i>Nilai Total
                                </th>
                                <th class="border-0 fw-semibold">Status</th>
                                <th class="border-0 fw-semibold text-center">
                                    <i class="bi bi-gear me-1"></i>Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barang as $index => $b)
                                <tr class="barang-row" data-name="{{ strtolower($b->nama_barang) }}"
                                    data-stok="{{ $b->stok }}">
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input row-checkbox" type="checkbox"
                                                value="{{ $b->id }}">
                                        </div>
                                    </td>
                                    <td class="fw-medium">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                                <i class="bi bi-box text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $b->nama_barang }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ $b->stok > 10 ? 'bg-success' : ($b->stok > 0 ? 'bg-warning' : 'bg-danger') }}">
                                            {{ $b->stok }} unit
                                        </span>
                                    </td>
                                    <td class="fw-semibold">Rp {{ number_format($b->harga) }}</td>
                                    <td class="fw-semibold text-success">Rp {{ number_format($b->harga * $b->stok) }}</td>
                                    <td>
                                        @if ($b->stok > 10)
                                            <span class="badge bg-success-subtle text-success">
                                                <i class="bi bi-check-circle me-1"></i>Tersedia
                                            </span>
                                        @elseif($b->stok > 0)
                                            <span class="badge bg-warning-subtle text-warning">
                                                <i class="bi bi-exclamation-triangle me-1"></i>Menipis
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger">
                                                <i class="bi bi-x-circle me-1"></i>Habis
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-outline-info btn-sm"
                                                onclick="viewDetail({{ $b->id }})" title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editBarangModal{{ $b->id }}" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-outline-danger btn-sm"
                                                onclick="deleteItem({{ $b->id }}, '{{ $b->nama_barang }}')"
                                                title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-box display-1 text-muted"></i>
                    <h5 class="mt-3 text-muted">Belum ada data barang</h5>
                    <p class="text-muted">Klik tombol "Tambah Barang" untuk menambah data pertama</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBarangModal">
                        <i class="bi bi-plus-circle me-2"></i>
                        Tambah Barang Pertama
                    </button>
                </div>
            @endif
        </div>

        @if ($barang->count() > 0)
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Menampilkan {{ $barang->count() }} data barang
                    </small>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-danger" onclick="bulkDelete()" disabled id="bulkDeleteBtn">
                            <i class="bi bi-trash me-1"></i>
                            Hapus Terpilih
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Grid View (Hidden by default) -->
    <div id="gridView" class="row g-3" style="display: none;">
        @foreach ($barang as $b)
            <div class="col-md-4 barang-card" data-name="{{ strtolower($b->nama_barang) }}"
                data-stok="{{ $b->stok }}">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-box text-primary fs-4"></i>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" onclick="viewDetail({{ $b->id }})">
                                            <i class="bi bi-eye me-2"></i>Detail
                                        </a></li>
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                            data-bs-target="#editBarangModal{{ $b->id }}">
                                            <i class="bi bi-pencil me-2"></i>Edit
                                        </a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item text-danger"
                                            onclick="deleteItem({{ $b->id }}, '{{ $b->nama_barang }}')">
                                            <i class="bi bi-trash me-2"></i>Hapus
                                        </a></li>
                                </ul>
                            </div>
                        </div>
                        <h5 class="card-title">{{ $b->nama_barang }}</h5>
                        <p class="text-muted small mb-3">ID: {{ $b->id }}</p>

                        <div class="row text-center mb-3">
                            <div class="col-6">
                                <h6 class="text-muted mb-1">Stok</h6>
                                <span
                                    class="badge {{ $b->stok > 10 ? 'bg-success' : ($b->stok > 0 ? 'bg-warning' : 'bg-danger') }} fs-6">
                                    {{ $b->stok }}
                                </span>
                            </div>
                            <div class="col-6">
                                <h6 class="text-muted mb-1">Harga</h6>
                                <div class="fw-semibold">Rp {{ number_format($b->harga) }}</div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            @if ($b->stok > 10)
                                <span class="badge bg-success-subtle text-success">
                                    <i class="bi bi-check-circle me-1"></i>Tersedia
                                </span>
                            @elseif($b->stok > 0)
                                <span class="badge bg-warning-subtle text-warning">
                                    <i class="bi bi-exclamation-triangle me-1"></i>Menipis
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger">
                                    <i class="bi bi-x-circle me-1"></i>Habis
                                </span>
                            @endif
                            <small class="text-success fw-semibold">
                                Total: Rp {{ number_format($b->harga * $b->stok) }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Include Modals -->
    @include('barang.create')

    @foreach ($barang as $b)
        @include('barang.edit', ['barang' => $b])
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

        .table th {
            font-size: 0.875rem;
            padding: 1rem 0.75rem;
        }

        .table td {
            padding: 1rem 0.75rem;
            vertical-align: middle;
        }

        .barang-row:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }

        .card {
            transition: all 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .btn-group .btn {
            border: 1px solid #dee2e6;
        }

        .btn-group .btn:hover {
            z-index: 2;
        }

        /* Modal Styles */
        .modal-lg {
            max-width: 800px;
        }

        .modal-header {
            border-bottom: 1px solid #dee2e6;
        }

        .modal-footer {
            border-top: 1px solid #dee2e6;
        }

        .preview-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        /* Form validation styles */
        .was-validated .form-control:valid,
        .form-control.is-valid {
            border-color: #198754;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='m2.3 6.73.4-.12 1.63-1.63L6.7 2.64l.7.7-2.35 2.35-.7.7-.7-.7-1.4-1.4z'/%3e%3c/svg%3e");
        }

        .was-validated .form-control:invalid,
        .form-control.is-invalid {
            border-color: #dc3545;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23dc3545' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 3.6.7.7M5.8 8.2l.7-.7m-.7 0L8.2 5.8'/%3e%3c/svg%3e");
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('.barang-row');
            const cards = document.querySelectorAll('.barang-card');

            // Filter table rows
            rows.forEach(row => {
                const name = row.dataset.name;
                row.style.display = name.includes(searchTerm) ? '' : 'none';
            });

            // Filter grid cards
            cards.forEach(card => {
                const name = card.dataset.name;
                card.style.display = name.includes(searchTerm) ? '' : 'none';
            });
        });

        // Status filter
        document.getElementById('statusFilter').addEventListener('change', function() {
            const status = this.value;
            const rows = document.querySelectorAll('.barang-row');
            const cards = document.querySelectorAll('.barang-card');

            const filterItems = (items) => {
                items.forEach(item => {
                    const stok = parseInt(item.dataset.stok);
                    let show = true;

                    if (status === 'available' && stok <= 0) show = false;
                    if (status === 'empty' && stok > 0) show = false;
                    if (status === 'low' && (stok >= 10 || stok <= 0)) show = false;

                    item.style.display = show ? '' : 'none';
                });
            };

            filterItems(rows);
            filterItems(cards);
        });

        // View toggle
        document.querySelectorAll('[data-view]').forEach(btn => {
            btn.addEventListener('click', function() {
                const view = this.dataset.view;

                // Update active button
                document.querySelectorAll('[data-view]').forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                // Toggle views
                if (view === 'table') {
                    document.getElementById('tableView').style.display = 'block';
                    document.getElementById('gridView').style.display = 'none';
                } else {
                    document.getElementById('tableView').style.display = 'none';
                    document.getElementById('gridView').style.display = 'flex';
                }
            });
        });

        // Select all functionality
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
            toggleBulkDelete();
        });

        // Individual checkbox change
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('row-checkbox')) {
                toggleBulkDelete();
            }
        });

        function toggleBulkDelete() {
            const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
            const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
            bulkDeleteBtn.disabled = checkedBoxes.length === 0;
        }

        // Delete item function
        function deleteItem(id, name) {
            if (confirm(
                    `Apakah Anda yakin ingin menghapus barang "${name}"?\n\nData yang sudah dihapus tidak dapat dikembalikan.`
                )) {
                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/barang/${id}`;
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Bulk delete function
        function bulkDelete() {
            const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
            if (checkedBoxes.length === 0) return;

            if (confirm(
                    `Apakah Anda yakin ingin menghapus ${checkedBoxes.length} barang yang dipilih?\n\nData yang sudah dihapus tidak dapat dikembalikan.`
                )) {
                showToast('Fitur bulk delete akan diimplementasikan', 'info');
            }
        }

        // View detail function
        function viewDetail(id) {
            showToast('Fitur detail akan diimplementasikan', 'info');
        }

        // Export functions
        function exportData(type) {
            showToast(`Export ${type.toUpperCase()} akan diimplementasikan`, 'info');
        }

        function printTable() {
            window.print();
        }

        // Toast function
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `alert alert-${type} position-fixed`;
            toast.style.cssText = 'top: 100px; right: 20px; z-index: 9999; min-width: 300px;';
            toast.innerHTML = `
                <i class="bi bi-${type === 'success' ? 'check-circle' : type === 'info' ? 'info-circle' : 'exclamation-triangle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
            `;

            document.body.appendChild(toast);

            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 3000);
        }

        // Form validation
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();

        // Auto open modal if there are validation errors
        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                // Check if it's create or edit error based on old input
                @if (old('_method') == 'PUT')
                    // This is an edit error - find the correct modal
                    const editModals = document.querySelectorAll('[id^="editBarangModal"]');
                    // You might need additional logic here to determine which edit modal to open
                @else
                    // This is a create error
                    const createModal = new bootstrap.Modal(document.getElementById('createBarangModal'));
                    createModal.show();
                @endif
            });
        @endif
    </script>
@endpush
