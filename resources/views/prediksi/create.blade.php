<!-- Modal Create Prediksi -->
<div class="modal fade" id="createPrediksiModal" tabindex="-1" aria-labelledby="createPrediksiModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('prediksi.store') }}" method="POST" id="prediksiForm" class="needs-validation"
                novalidate>
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="createPrediksiModalLabel">
                        <i class="bi bi-plus-circle me-2"></i>
                        Tambah Prediksi Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Pilih Barang -->
                        <div class="col-12">
                            <label for="barang_id" class="form-label fw-semibold">
                                <i class="bi bi-box text-primary me-1"></i>
                                Pilih Barang <span class="text-danger">*</span>
                            </label>
                            <select name="barang_id" id="barang_id"
                                class="form-select @error('barang_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Barang --</option>
                                @foreach ($barang as $b)
                                    <option value="{{ $b->id }}"
                                        {{ old('barang_id') == $b->id ? 'selected' : '' }}>
                                        {{ $b->nama_barang }}
                                    </option>
                                @endforeach
                            </select>
                            @error('barang_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="invalid-feedback">
                                Barang harus dipilih
                            </div>
                        </div>

                        <!-- Dataset Periode -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-calendar-data text-info me-1"></i>
                                Dataset Periode <span class="text-danger">*</span>
                            </label>
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <!-- Data Info Section -->
                                    <div id="dataInfoSection" style="display: none;">
                                        <div class="alert alert-info border-0 mb-3">
                                            <i class="bi bi-info-circle text-info me-2"></i>
                                            <strong>Informasi Dataset:</strong><br>
                                            <span id="totalDataText"></span>
                                        </div>

                                        <!-- Button Options -->
                                        <div class="mb-3">
                                            <h6 class="mb-3 text-muted fw-semibold">Pilih Dataset:</h6>
                                            <div class="d-grid gap-2 d-md-flex">
                                                <button type="button" class="btn btn-outline-primary"
                                                    id="useAllDataBtn" onclick="selectAllData()">
                                                    <i class="bi bi-database me-2"></i>Gunakan Semua Data
                                                </button>
                                                <button type="button" class="btn btn-outline-primary"
                                                    id="selectRangeBtn" onclick="showRangeSelection()">
                                                    <i class="bi bi-funnel me-2"></i>Pilih Range Data
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Range Selection -->
                                        <div id="rangeSelectionArea" class="border rounded p-3 bg-white"
                                            style="display: none;">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">
                                                        <i class="bi bi-calendar-check text-success me-1"></i>
                                                        Dari Bulan:
                                                    </label>
                                                    <select class="form-select" id="startMonthSelect">
                                                        <option value="">-- Pilih Bulan Mulai --</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">
                                                        <i class="bi bi-calendar-x text-danger me-1"></i>
                                                        Sampai Bulan:
                                                    </label>
                                                    <select class="form-select" id="endMonthSelect">
                                                        <option value="">-- Pilih Bulan Akhir --</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="mt-3 d-flex gap-2">
                                                <button type="button" class="btn btn-success btn-sm"
                                                    onclick="applyRangeSelection()">
                                                    <i class="bi bi-check-circle me-1"></i>Terapkan Pilihan
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                                    onclick="cancelRangeSelection()">
                                                    <i class="bi bi-x-circle me-1"></i>Batal
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Selected Dataset Info -->
                                        <div id="selectedDatasetInfo" class="alert alert-success border-0 mt-3"
                                            style="display: none;">
                                            <i class="bi bi-check-circle text-success me-2"></i>
                                            <strong>Dataset Terpilih:</strong> <span
                                                id="selectedDatasetText"></span><br>
                                            <span id="selectedCountText"></span>
                                        </div>
                                    </div>

                                    <div id="noBarangSelected" class="text-center text-muted py-4">
                                        <i class="bi bi-info-circle display-6 text-muted mb-3"></i>
                                        <p class="mb-0">Pilih barang terlebih dahulu untuk melihat data yang tersedia
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden inputs for form submission -->
                            <input type="hidden" name="use_all_data" id="useAllDataInput" value="1">
                            <input type="hidden" name="start_year" id="startYearInput">
                            <input type="hidden" name="start_month" id="startMonthInput">
                            <input type="hidden" name="end_year" id="endYearInput">
                            <input type="hidden" name="end_month" id="endMonthInput">

                            @error('start_year')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            @error('start_month')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            @error('end_year')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            @error('end_month')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Periode Prediksi -->
                        <div class="col-md-6">
                            <label for="periode" class="form-label fw-semibold">
                                <i class="bi bi-calendar-week text-warning me-1"></i>
                                Periode Prediksi
                            </label>
                            <input type="hidden" name="periode" value="3">
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-calendar-month text-muted"></i>
                                </span>
                                <input type="text" class="form-control bg-light" value="3 Bulan ke Depan"
                                    readonly>
                                <span class="input-group-text">bulan</span>
                            </div>
                            <small class="form-text text-muted">Sistem akan memprediksi untuk 3 bulan ke depan</small>
                        </div>

                        <!-- Metode Prediksi (Read-only info) -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-gear text-secondary me-1"></i>
                                Metode Prediksi
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-graph-up text-muted"></i>
                                </span>
                                <input type="text" class="form-control bg-light" value="Regresi Linear" readonly>
                            </div>
                            <small class="form-text text-muted">Metode yang digunakan untuk prediksi</small>
                        </div>

                        <!-- Preview Card -->
                        <div class="col-12">
                            <div class="card bg-light border-0">
                                <div class="card-header bg-transparent border-0 pb-0">
                                    <h6 class="mb-0 fw-semibold">
                                        <i class="bi bi-eye text-info me-1"></i>
                                        Preview Prediksi
                                    </h6>
                                </div>
                                <div class="card-body pt-2">
                                    <div class="row text-center">
                                        <div class="col-3">
                                            <small class="text-muted d-block">Barang</small>
                                            <span class="fw-semibold" id="preview-nama">-</span>
                                        </div>
                                        <div class="col-3">
                                            <small class="text-muted d-block">Dataset</small>
                                            <span class="fw-semibold" id="preview-dataset">-</span>
                                        </div>
                                        <div class="col-3">
                                            <small class="text-muted d-block">Periode</small>
                                            <span class="fw-semibold" id="preview-periode">3 Bulan</span>
                                        </div>
                                        <div class="col-3">
                                            <small class="text-muted d-block">Metode</small>
                                            <span class="fw-semibold" id="preview-metode">Regresi Linear</span>
                                        </div>
                                    </div>
                                    <hr class="my-2">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <small class="text-muted">Status Dataset:</small>
                                            <div class="fw-bold fs-6" id="preview-status">
                                                <span class="badge bg-secondary">Belum dipilih</span>
                                            </div>
                                        </div>
                                        {{-- <div class="col-6">
                                            <small class="text-muted">Estimasi Waktu:</small>
                                            <div class="fw-semibold" id="preview-waktu">~2-5 detik</div>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Penting -->
                        <div class="col-12">
                            <div class="alert alert-info border-0">
                                <div class="d-flex">
                                    <i class="bi bi-lightbulb text-info me-3 mt-1"></i>
                                    <div>
                                        <h6 class="alert-heading mb-2 fw-semibold">Informasi Penting</h6>
                                        <ul class="mb-0 small lh-lg">
                                            <li>Dataset minimal <strong>5 bulan data</strong> diperlukan untuk prediksi
                                                yang akurat</li>
                                            <li>Sistem akan menghitung <strong>MAPE</strong> (Mean Absolute Percentage
                                                Error) untuk mengukur akurasi</li>
                                            <li>Prediksi akan menghasilkan nilai untuk <strong>3 bulan ke depan</strong>
                                            </li>
                                            <li>Semakin banyak data historis, semakin akurat prediksi yang dihasilkan
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                        <i class="bi bi-check-circle me-1"></i>
                        Buat Prediksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let availableMonthsData = [];
    let selectedDatasetType = 'all';
    let startMonth = null;
    let endMonth = null;

    document.addEventListener('DOMContentLoaded', function() {
        const barangSelect = document.getElementById('barang_id');
        const submitBtn = document.getElementById('submitBtn');

        // Function untuk update preview
        const updatePreview = () => {
            const selectedOption = barangSelect.options[barangSelect.selectedIndex];
            const nama = selectedOption.value ? selectedOption.text : '-';

            document.getElementById('preview-nama').textContent = nama;

            // Update dataset info
            if (selectedDatasetType === 'all' && availableMonthsData.length > 0) {
                let totalMonths = 0;
                availableMonthsData.forEach(yearData => {
                    yearData.months.forEach(monthData => {
                        if (monthData.available) totalMonths++;
                    });
                });
                document.getElementById('preview-dataset').textContent = `${totalMonths} bulan`;
            } else if (selectedDatasetType === 'range') {
                const selectedText = document.getElementById('selectedDatasetText').textContent;
                const countText = document.getElementById('selectedCountText').textContent;
                document.getElementById('preview-dataset').textContent = countText || 'Range dipilih';
            } else {
                document.getElementById('preview-dataset').textContent = '-';
            }

            // Update status
            if (selectedOption.value && (selectedDatasetType === 'all' || selectedDatasetType ===
                    'range')) {
                document.getElementById('preview-status').innerHTML =
                    '<span class="badge bg-success">Siap untuk prediksi</span>';
            } else {
                document.getElementById('preview-status').innerHTML =
                    '<span class="badge bg-secondary">Belum siap</span>';
            }
        };

        // Event listener untuk perubahan barang
        barangSelect.addEventListener('change', function() {
            const barangId = this.value;

            if (!barangId) {
                document.getElementById('dataInfoSection').style.display = 'none';
                document.getElementById('noBarangSelected').style.display = 'block';
                submitBtn.disabled = true;
                resetDatasetSelection();
                updatePreview();
                return;
            }

            // Show loading
            document.getElementById('noBarangSelected').innerHTML = `
            <div class="text-center">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mb-0">Memuat data yang tersedia...</p>
            </div>
        `;

            // Fetch available data
            fetch(`/prediksi/get-available-data?barang_id=${barangId}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Data received:', data);
                    availableMonthsData = data;
                    displayDataInfo();
                    document.getElementById('dataInfoSection').style.display = 'block';
                    document.getElementById('noBarangSelected').style.display = 'none';
                    resetDatasetSelection();
                    updatePreview();
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('noBarangSelected').innerHTML = `
                    <div class="text-center">
                        <i class="bi bi-exclamation-triangle display-6 text-warning mb-3"></i>
                        <p class="mb-0 text-danger">Error memuat data: ${error.message}</p>
                    </div>
                `;
                });
        });

        // Reset form when modal is hidden
        document.getElementById('createPrediksiModal').addEventListener('hidden.bs.modal', function() {
            this.querySelector('form').reset();
            this.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

            // Reset variables
            availableMonthsData = [];
            selectedDatasetType = 'all';
            startMonth = null;
            endMonth = null;

            // Reset UI
            document.getElementById('dataInfoSection').style.display = 'none';
            document.getElementById('noBarangSelected').style.display = 'block';
            document.getElementById('noBarangSelected').innerHTML = `
            <div class="text-center text-muted py-4">
                <i class="bi bi-info-circle display-6 text-muted mb-3"></i>
                <p class="mb-0">Pilih barang terlebih dahulu untuk melihat data yang tersedia</p>
            </div>
        `;

            resetDatasetSelection();
            updatePreview();
        });

        // Initialize
        updatePreview();
    });

    function displayDataInfo() {
        let totalMonths = 0;
        let dateRange = '';
        let earliestDate = null;
        let latestDate = null;

        // Calculate total available months and date range
        availableMonthsData.forEach(yearData => {
            yearData.months.forEach(monthData => {
                if (monthData.available) {
                    totalMonths++;
                    const currentDate = new Date(yearData.year, monthData.number - 1);

                    if (!earliestDate || currentDate < earliestDate) {
                        earliestDate = currentDate;
                    }
                    if (!latestDate || currentDate > latestDate) {
                        latestDate = currentDate;
                    }
                }
            });
        });

        if (earliestDate && latestDate) {
            const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];

            const startStr = `${monthNames[earliestDate.getMonth()]} ${earliestDate.getFullYear()}`;
            const endStr = `${monthNames[latestDate.getMonth()]} ${latestDate.getFullYear()}`;
            dateRange = `${startStr} - ${endStr}`;
        }

        const statusClass = totalMonths >= 5 ? 'text-success' : 'text-danger';
        const statusIcon = totalMonths >= 5 ? 'bi-check-circle' : 'bi-exclamation-triangle';
        const statusText = totalMonths >= 5 ? 'Cukup untuk prediksi' : 'Minimal 5 bulan diperlukan';

        document.getElementById('totalDataText').innerHTML = `
        Total <strong>${totalMonths}</strong> bulan data tersedia<br>
        Periode: <strong>${dateRange}</strong><br>
        <i class="bi ${statusIcon} me-1"></i>
        <span class="${statusClass}">${statusText}</span>
    `;

        // Populate month selectors
        populateMonthSelectors();
    }

    function populateMonthSelectors() {
        const startSelect = document.getElementById('startMonthSelect');
        const endSelect = document.getElementById('endMonthSelect');

        startSelect.innerHTML = '<option value="">-- Pilih Bulan Mulai --</option>';
        endSelect.innerHTML = '<option value="">-- Pilih Bulan Akhir --</option>';

        const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        availableMonthsData.forEach(yearData => {
            yearData.months.forEach(monthData => {
                if (monthData.available) {
                    const value = `${yearData.year}-${monthData.number}`;
                    const text = `${monthNames[monthData.number - 1]} ${yearData.year}`;

                    startSelect.innerHTML += `<option value="${value}">${text}</option>`;
                    endSelect.innerHTML += `<option value="${value}">${text}</option>`;
                }
            });
        });
    }

    function selectAllData() {
        selectedDatasetType = 'all';
        document.getElementById('rangeSelectionArea').style.display = 'none';
        document.getElementById('useAllDataBtn').classList.add('btn-primary');
        document.getElementById('useAllDataBtn').classList.remove('btn-outline-primary');
        document.getElementById('selectRangeBtn').classList.add('btn-outline-primary');
        document.getElementById('selectRangeBtn').classList.remove('btn-primary');

        // Update form inputs
        document.getElementById('useAllDataInput').value = '1';
        document.getElementById('startYearInput').value = '';
        document.getElementById('startMonthInput').value = '';
        document.getElementById('endYearInput').value = '';
        document.getElementById('endMonthInput').value = '';

        // Show selected info
        let totalMonths = 0;
        availableMonthsData.forEach(yearData => {
            yearData.months.forEach(monthData => {
                if (monthData.available) totalMonths++;
            });
        });

        document.getElementById('selectedDatasetText').textContent = 'Semua data yang tersedia';
        document.getElementById('selectedCountText').textContent = `${totalMonths} bulan data`;
        document.getElementById('selectedDatasetInfo').style.display = 'block';

        document.getElementById('submitBtn').disabled = totalMonths < 5;
        updatePreview();
    }

    function showRangeSelection() {
        selectedDatasetType = 'range';
        document.getElementById('rangeSelectionArea').style.display = 'block';
        document.getElementById('selectRangeBtn').classList.add('btn-primary');
        document.getElementById('selectRangeBtn').classList.remove('btn-outline-primary');
        document.getElementById('useAllDataBtn').classList.add('btn-outline-primary');
        document.getElementById('useAllDataBtn').classList.remove('btn-primary');

        document.getElementById('selectedDatasetInfo').style.display = 'none';
        document.getElementById('submitBtn').disabled = true;
        updatePreview();
    }

    function applyRangeSelection() {
        const startValue = document.getElementById('startMonthSelect').value;
        const endValue = document.getElementById('endMonthSelect').value;

        if (!startValue || !endValue) {
            alert('Pilih bulan mulai dan bulan akhir terlebih dahulu');
            return;
        }

        const [startYear, startMonthNum] = startValue.split('-');
        const [endYear, endMonthNum] = endValue.split('-');

        const startDate = new Date(parseInt(startYear), parseInt(startMonthNum) - 1);
        const endDate = new Date(parseInt(endYear), parseInt(endMonthNum) - 1);

        if (startDate > endDate) {
            alert('Bulan mulai tidak boleh lebih besar dari bulan akhir');
            return;
        }

        // Count months in range
        let monthsInRange = 0;
        availableMonthsData.forEach(yearData => {
            yearData.months.forEach(monthData => {
                if (monthData.available) {
                    const currentDate = new Date(yearData.year, monthData.number - 1);
                    if (currentDate >= startDate && currentDate <= endDate) {
                        monthsInRange++;
                    }
                }
            });
        });

        if (monthsInRange < 5) {
            alert('Range yang dipilih harus memiliki minimal 5 bulan data');
            return;
        }

        // Update form inputs
        document.getElementById('useAllDataInput').value = '0';
        document.getElementById('startYearInput').value = startYear;
        document.getElementById('startMonthInput').value = startMonthNum;
        document.getElementById('endYearInput').value = endYear;
        document.getElementById('endMonthInput').value = endMonthNum;

        // Show selected info
        const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        const startText = `${monthNames[parseInt(startMonthNum) - 1]} ${startYear}`;
        const endText = `${monthNames[parseInt(endMonthNum) - 1]} ${endYear}`;

        document.getElementById('selectedDatasetText').textContent = `${startText} - ${endText}`;
        document.getElementById('selectedCountText').textContent = `${monthsInRange} bulan data`;
        document.getElementById('selectedDatasetInfo').style.display = 'block';
        document.getElementById('rangeSelectionArea').style.display = 'none';

        document.getElementById('submitBtn').disabled = false;
        updatePreview();
    }

    function cancelRangeSelection() {
        document.getElementById('rangeSelectionArea').style.display = 'none';
        document.getElementById('startMonthSelect').value = '';
        document.getElementById('endMonthSelect').value = '';

        // Reset to all data
        selectAllData();
    }

    function resetDatasetSelection() {
        selectedDatasetType = 'all';
        startMonth = null;
        endMonth = null;

        document.getElementById('rangeSelectionArea').style.display = 'none';
        document.getElementById('selectedDatasetInfo').style.display = 'none';
        document.getElementById('startMonthSelect').value = '';
        document.getElementById('endMonthSelect').value = '';

        // Reset button states
        document.getElementById('useAllDataBtn').classList.remove('btn-primary');
        document.getElementById('useAllDataBtn').classList.add('btn-outline-primary');
        document.getElementById('selectRangeBtn').classList.remove('btn-primary');
        document.getElementById('selectRangeBtn').classList.add('btn-outline-primary');

        // Reset form inputs
        document.getElementById('useAllDataInput').value = '1';
        document.getElementById('startYearInput').value = '';
        document.getElementById('startMonthInput').value = '';
        document.getElementById('endYearInput').value = '';
        document.getElementById('endMonthInput').value = '';

        document.getElementById('submitBtn').disabled = true;
    }

    // Function to update preview when dataset changes
    function updatePreview() {
        if (typeof document.getElementById('preview-nama') !== 'undefined') {
            const barangSelect = document.getElementById('barang_id');
            const selectedOption = barangSelect.options[barangSelect.selectedIndex];
            const nama = selectedOption.value ? selectedOption.text : '-';

            document.getElementById('preview-nama').textContent = nama;

            // Update dataset info in preview
            if (selectedDatasetType === 'all' && availableMonthsData.length > 0) {
                let totalMonths = 0;
                availableMonthsData.forEach(yearData => {
                    yearData.months.forEach(monthData => {
                        if (monthData.available) totalMonths++;
                    });
                });
                document.getElementById('preview-dataset').textContent = `${totalMonths} bulan`;
            } else if (selectedDatasetType === 'range') {
                const countText = document.getElementById('selectedCountText') ?
                    document.getElementById('selectedCountText').textContent : 'Range dipilih';
                document.getElementById('preview-dataset').textContent = countText;
            } else {
                document.getElementById('preview-dataset').textContent = '-';
            }

            // Update status
            if (selectedOption.value && (selectedDatasetType === 'all' || selectedDatasetType === 'range')) {
                const statusElement = document.getElementById('preview-status');
                if (statusElement) {
                    statusElement.innerHTML = '<span class="badge bg-success">Siap untuk prediksi</span>';
                }
            } else {
                const statusElement = document.getElementById('preview-status');
                if (statusElement) {
                    statusElement.innerHTML = '<span class="badge bg-secondary">Belum siap</span>';
                }
            }
        }
    }
</script>
