<!-- Modal Create Prediksi -->
<div class="modal fade" id="createPrediksiModal" tabindex="-1" aria-labelledby="createPrediksiModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createPrediksiModalLabel">Buat Prediksi Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('prediksi.store') }}" method="POST" id="prediksiForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="barang_id" class="form-label">Pilih Barang</label>
                        <select name="barang_id" id="barang_id" class="form-control" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach ($barang as $b)
                                <option value="{{ $b->id }}" {{ old('barang_id') == $b->id ? 'selected' : '' }}>
                                    {{ $b->nama_barang }}
                                </option>
                            @endforeach
                        </select>
                        @error('barang_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Dataset Periode</label>
                        <div class="border rounded p-3" style="background-color: #f8f9fa; min-height: 150px;">
                            <!-- Data Info Section -->
                            <div id="dataInfoSection" style="display: none;">
                                <div class="alert alert-info mb-3">
                                    <strong>Informasi Dataset:</strong><br>
                                    <span id="totalDataText"></span>
                                </div>

                                <!-- Button Options -->
                                <div class="mb-3">
                                    <h6 class="mb-2">Pilih Dataset:</h6>
                                    <div class="d-grid gap-2 d-md-flex">
                                        <button type="button" class="btn btn-primary" id="useAllDataBtn"
                                            onclick="selectAllData()">
                                            <i class="fas fa-database"></i> Gunakan Semua Data
                                        </button>
                                        <button type="button" class="btn btn-outline-primary" id="selectRangeBtn"
                                            onclick="showRangeSelection()">
                                            <i class="fas fa-filter"></i> Pilih Range Data
                                        </button>
                                    </div>
                                </div>

                                <!-- Range Selection -->
                                <div id="rangeSelectionArea" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label">Dari Bulan:</label>
                                            <select class="form-control" id="startMonthSelect">
                                                <option value="">-- Pilih Bulan Mulai --</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Sampai Bulan:</label>
                                            <select class="form-control" id="endMonthSelect">
                                                <option value="">-- Pilih Bulan Akhir --</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mt-2">
                                        <button type="button" class="btn btn-success btn-sm"
                                            onclick="applyRangeSelection()">
                                            <i class="fas fa-check"></i> Terapkan Pilihan
                                        </button>
                                        <button type="button" class="btn btn-secondary btn-sm"
                                            onclick="cancelRangeSelection()">
                                            <i class="fas fa-times"></i> Batal
                                        </button>
                                    </div>
                                </div>

                                <!-- Selected Dataset Info -->
                                <div id="selectedDatasetInfo" class="mt-3 p-2 bg-success text-white rounded small"
                                    style="display: none;">
                                    <strong>Dataset Terpilih:</strong> <span id="selectedDatasetText"></span><br>
                                    <span id="selectedCountText"></span>
                                </div>
                            </div>

                            <div id="noBarangSelected" class="text-center text-muted py-4">
                                <i class="fas fa-info-circle"></i><br>
                                Pilih barang terlebih dahulu untuk melihat data yang tersedia
                            </div>
                        </div>

                        <!-- Hidden inputs for form submission -->
                        <input type="hidden" name="use_all_data" id="useAllDataInput" value="1">
                        <input type="hidden" name="start_year" id="startYearInput">
                        <input type="hidden" name="start_month" id="startMonthInput">
                        <input type="hidden" name="end_year" id="endYearInput">
                        <input type="hidden" name="end_month" id="endMonthInput">

                        @error('start_year')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                        @error('start_month')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                        @error('end_year')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                        @error('end_month')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="periode" class="form-label">Jumlah Periode ke Depan</label>
                        <input type="hidden" name="periode" value="3">
                        <input type="text" class="form-control" value="3 Bulan ke Depan" readonly>
                        <small class="form-text text-muted">Sistem akan memprediksi penjualan untuk 3 bulan ke
                            depan</small>
                    </div>

                    <div class="alert alert-info">
                        <small>
                            <strong>Informasi:</strong><br>
                            - Sistem akan menggunakan metode Regresi Linear untuk prediksi<br>
                            - Prediksi akan dilakukan untuk 3 bulan ke depan<br>
                            - Pastikan dataset yang dipilih memiliki minimal 5 bulan data<br>
                            - Hasil prediksi akan menampilkan nilai MAPE (Mean Absolute Percentage Error)
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>Buat Prediksi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .month-option {
        font-size: 14px;
        padding: 5px 10px;
    }

    .alert-info {
        background-color: #d1ecf1;
        border-color: #bee5eb;
        color: #0c5460;
    }

    .btn-outline-primary:hover {
        color: #fff;
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    #rangeSelectionArea {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        border: 1px solid #dee2e6;
    }
</style>

<script>
    let availableMonthsData = [];
    let selectedDatasetType = 'all'; // 'all' or 'range'
    let startMonth = null;
    let endMonth = null;

    document.getElementById('barang_id').addEventListener('change', function() {
        const barangId = this.value;

        if (!barangId) {
            document.getElementById('dataInfoSection').style.display = 'none';
            document.getElementById('noBarangSelected').style.display = 'block';
            document.getElementById('submitBtn').disabled = true;
            resetDatasetSelection();
            return;
        }

        // Show loading
        document.getElementById('noBarangSelected').innerHTML =
            '<i class="fas fa-spinner fa-spin"></i><br>Memuat data...';

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
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('noBarangSelected').innerHTML =
                    `<i class="fas fa-exclamation-triangle text-warning"></i><br>Error memuat data: ${error.message}`;
            });
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

        document.getElementById('totalDataText').innerHTML =
            `Total <strong>${totalMonths}</strong> bulan data tersedia<br>
             Periode: <strong>${dateRange}</strong><br>
             ${totalMonths >= 5 ? '<span class="text-success">✓ Cukup untuk prediksi</span>' : '<span class="text-danger">⚠ Minimal 5 bulan diperlukan</span>'}`;

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
</script>
