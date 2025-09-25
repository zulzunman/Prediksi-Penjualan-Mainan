@extends('layouts.app')

@section('title', 'Data Prediksi - Aplikasi Toko')

@section('content')
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">
                <i class="bi bi-graph-up-arrow text-primary me-2"></i>
                Data Prediksi
            </h1>
            <p class="text-muted mb-0">Kelola dan analisis prediksi penjualan toko Anda</p>
        </div>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPrediksiModal">
                <i class="bi bi-plus-circle me-2"></i>
                Buat Prediksi
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

    {{-- <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary text-white rounded me-3">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <div>
                            <h6 class="card-title text-muted mb-1">Total Prediksi</h6>
                            <h4 class="mb-0">{{ $prediksi->count() }}</h4>
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
                            <h6 class="card-title text-muted mb-1">Akurasi Tinggi</h6>
                            <h4 class="mb-0">{{ $prediksi->where('mape', '<=', 10)->count() }}</h4>
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
                            <h6 class="card-title text-muted mb-1">Akurasi Rendah</h6>
                            <h4 class="mb-0">{{ $prediksi->where('mape', '>', 50)->count() }}</h4>
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
                            <i class="bi bi-calendar-month"></i>
                        </div>
                        <div>
                            <h6 class="card-title text-muted mb-1">Bulan Ini</h6>
                            <h4 class="mb-0">
                                {{ $prediksi->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count() }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Search and Filter Section -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" id="searchInput" class="form-control border-start-0"
                            placeholder="Cari nama barang...">
                    </div>
                </div>
                {{-- <div class="col-md-2">
                    <select id="periodeFilter" class="form-select">
                        <option value="">Semua Periode</option>
                        <option value="3">3 Bulan</option>
                        <option value="6">6 Bulan</option>
                        <option value="12">12 Bulan</option>
                    </select>
                </div>
                <div class="col-md-2">
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
                    Data Prediksi
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
            @if ($prediksi->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="prediksiTable">
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
                                    <i class="bi bi-info-circle me-1"></i>Dataset Info
                                </th>
                                {{-- <th class="border-0 fw-semibold">
                                    <i class="bi bi-gear me-1"></i>Metode
                                </th> --}}
                                <th class="border-0 fw-semibold">
                                    <i class="bi bi-calendar me-1"></i>Periode
                                </th>
                                <th class="border-0 fw-semibold">
                                    <i class="bi bi-bullseye me-1"></i>MAPE
                                </th>
                                {{-- <th class="border-0 fw-semibold">
                                    <i class="bi bi-graph-up me-1"></i>Total Prediksi
                                </th> --}}
                                <th class="border-0 fw-semibold">Tanggal Dibuat</th>
                                <th class="border-0 fw-semibold text-center">
                                    <i class="bi bi-gear me-1"></i>Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($prediksi as $index => $p)
                                @php
                                    // Normalisasi hasil_prediksi untuk menghitung total
                                    $raw = $p->hasil_prediksi;
                                    $items = [];
                                    if (
                                        is_array($raw) &&
                                        isset($raw[0]) &&
                                        is_array($raw[0]) &&
                                        array_key_exists('label', $raw[0])
                                    ) {
                                        $items = $raw;
                                    } elseif (is_array($raw)) {
                                        foreach ($raw as $lbl => $val) {
                                            $items[] = ['label' => $lbl, 'nilai' => $val];
                                        }
                                    }
                                    $totalPrediksi = collect($items)->sum('nilai');
                                @endphp
                                <tr class="prediksi-row" data-name="{{ strtolower($p->barang->nama_barang ?? '') }}"
                                    data-metode="{{ $p->metode }}" data-mape="{{ $p->mape }}"
                                    data-periode="{{ $p->periode }}">
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input row-checkbox" type="checkbox"
                                                value="{{ $p->id }}">
                                        </div>
                                    </td>
                                    <td class="fw-medium">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                                <i class="bi bi-graph-up text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $p->barang->nama_barang ?? 'N/A' }}</div>
                                                <small class="text-muted">ID: {{ $p->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $p->dataset_info ?? 'N/A' }}
                                        </small>
                                    </td>
                                    {{-- <td>
                                        <span class="badge bg-info-subtle text-info">
                                            {{ ucfirst(str_replace('_', ' ', $p->metode)) }}
                                        </span>
                                    </td> --}}
                                    <td>
                                        <span class="badge bg-secondary-subtle text-secondary">
                                            {{ $p->periode }} bulan
                                        </span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ $p->mape <= 10 ? 'bg-success' : ($p->mape <= 20 ? 'bg-warning' : ($p->mape <= 50 ? 'bg-info' : 'bg-danger')) }}">
                                            {{ $p->mape }}%
                                        </span>
                                    </td>
                                    {{-- <td class="fw-semibold text-success">
                                        {{ number_format($totalPrediksi) }} unit
                                    </td> --}}
                                    <td>{{ $p->created_at->format('d M Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-outline-info btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#showPrediksiModal{{ $p->id }}" title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-danger btn-sm"
                                                onclick="deleteItem({{ $p->id }}, '{{ $p->barang->nama_barang ?? 'N/A' }}')"
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
                    <i class="bi bi-graph-up display-1 text-muted"></i>
                    <h5 class="mt-3 text-muted">Belum ada data prediksi</h5>
                    <p class="text-muted">Klik tombol "Buat Prediksi" untuk membuat prediksi pertama</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPrediksiModal">
                        <i class="bi bi-plus-circle me-2"></i>
                        Buat Prediksi Pertama
                    </button>
                </div>
            @endif
        </div>

        @if ($prediksi->count() > 0)
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Menampilkan {{ $prediksi->count() }} data prediksi
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
        @foreach ($prediksi as $p)
            @php
                $raw = $p->hasil_prediksi;
                $items = [];
                if (is_array($raw) && isset($raw[0]) && is_array($raw[0]) && array_key_exists('label', $raw[0])) {
                    $items = $raw;
                } elseif (is_array($raw)) {
                    foreach ($raw as $lbl => $val) {
                        $items[] = ['label' => $lbl, 'nilai' => $val];
                    }
                }
                $totalPrediksi = collect($items)->sum('nilai');
            @endphp
            <div class="col-md-4 prediksi-card" data-name="{{ strtolower($p->barang->nama_barang ?? '') }}"
                data-metode="{{ $p->metode }}" data-mape="{{ $p->mape }}" data-periode="{{ $p->periode }}">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-graph-up text-primary fs-4"></i>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" data-bs-toggle="modal"
                                            data-bs-target="#showPrediksiModal{{ $p->id }}">
                                            <i class="bi bi-eye me-2"></i>Detail
                                        </a></li>
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                            data-bs-target="#editPrediksiModal{{ $p->id }}">
                                            <i class="bi bi-pencil me-2"></i>Edit
                                        </a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item text-danger"
                                            onclick="deleteItem({{ $p->id }}, '{{ $p->barang->nama_barang ?? 'N/A' }}')">
                                            <i class="bi bi-trash me-2"></i>Hapus
                                        </a></li>
                                </ul>
                            </div>
                        </div>
                        <h5 class="card-title">{{ $p->barang->nama_barang ?? 'N/A' }}</h5>
                        <p class="text-muted small mb-3">{{ ucfirst(str_replace('_', ' ', $p->metode)) }}</p>

                        <div class="row text-center mb-3">
                            <div class="col-6">
                                <h6 class="text-muted mb-1">MAPE</h6>
                                <span
                                    class="badge {{ $p->mape <= 10 ? 'bg-success' : ($p->mape <= 20 ? 'bg-warning' : ($p->mape <= 50 ? 'bg-info' : 'bg-danger')) }} fs-6">
                                    {{ $p->mape }}%
                                </span>
                            </div>
                            <div class="col-6">
                                <h6 class="text-muted mb-1">Periode</h6>
                                <div class="fw-semibold">{{ $p->periode }} bulan</div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            @if ($p->mape <= 10)
                                <span class="badge bg-success-subtle text-success">
                                    <i class="bi bi-check-circle me-1"></i>Sangat Baik
                                </span>
                            @elseif($p->mape <= 20)
                                <span class="badge bg-warning-subtle text-warning">
                                    <i class="bi bi-exclamation-triangle me-1"></i>Baik
                                </span>
                            @elseif($p->mape <= 50)
                                <span class="badge bg-info-subtle text-info">
                                    <i class="bi bi-info-circle me-1"></i>Cukup
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger">
                                    <i class="bi bi-x-circle me-1"></i>Kurang
                                </span>
                            @endif
                            <small class="text-success fw-semibold">
                                Total: {{ number_format($totalPrediksi) }} unit
                            </small>
                        </div>

                        <div class="mt-3 text-center">
                            <small class="text-muted">Dibuat: {{ $p->created_at->format('d M Y') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Include Modals -->
    @include('prediksi.create')

    @foreach ($prediksi as $p)
        @include('prediksi.show', ['prediksi' => $p])
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

        .prediksi-row:hover {
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
            const rows = document.querySelectorAll('.prediksi-row');
            const cards = document.querySelectorAll('.prediksi-card');

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

        // Periode filter
        document.getElementById('periodeFilter').addEventListener('change', function() {
            const periode = this.value;
            filterData();
        });

        function filterData() {
            const periode = document.getElementById('periodeFilter').value;
            const rows = document.querySelectorAll('.prediksi-row');
            const cards = document.querySelectorAll('.prediksi-card');

            const filterItems = (items) => {
                items.forEach(item => {
                    const itemMetode = item.dataset.metode;
                    const itemMape = parseFloat(item.dataset.mape);
                    const itemPeriode = item.dataset.periode;
                    let show = true;

                    // Filter periode
                    if (periode && itemPeriode !== periode) show = false;

                    item.style.display = show ? '' : 'none';
                });
            };

            filterItems(rows);
            filterItems(cards);
        }

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
                    `Apakah Anda yakin ingin menghapus prediksi untuk "${name}"?\n\nData yang sudah dihapus tidak dapat dikembalikan.`
                )) {
                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/prediksi/${id}`;
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                                        @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Bulk delete function
        function bulkDelete() {
            if (!confirm(
                    "Apakah Anda yakin ingin menghapus semua data yang dipilih?\n\nData yang sudah dihapus tidak dapat dikembalikan."
                )) {
                return;
            }

            const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
            if (checkedBoxes.length === 0) return;

            // Buat form dengan data id terpilih
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/prediksi/bulk-delete`; // pastikan route ini sudah ada di web.php
            form.innerHTML = `
                @csrf
                @method('DELETE')
                ${Array.from(checkedBoxes).map(cb => `<input type="hidden" name="ids[]" value="${cb.value}">`).join('')}
            `;

            document.body.appendChild(form);
            form.submit();
        }

        // Export Data
        function exportData(type) {
            window.location.href = `/prediksi/export/${type}`;
        }

        // Print table
        function printTable() {
            const printContents = document.getElementById("prediksiTable").outerHTML;
            const newWindow = window.open('', '', 'width=800, height=600');
            newWindow.document.write('<html><head><title>Print</title>');
            newWindow.document.write('<link rel="stylesheet" href="{{ asset('css/app.css') }}">');
            newWindow.document.write('</head><body>');
            newWindow.document.write(printContents);
            newWindow.document.write('</body></html>');
            newWindow.document.close();
            newWindow.print();
        }
    </script>
@endpush
