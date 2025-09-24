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
                        <div class="border rounded p-3" style="background-color: #f8f9fa; min-height: 100px;">
                            <div id="dateSelectionArea" style="display: none;">
                                <!-- Year Selection -->
                                <div class="mb-3">
                                    <h6 class="mb-2">Pilih Tahun:</h6>
                                    <div id="yearSelection" class="d-flex flex-wrap gap-2">
                                        <!-- Years will be populated here -->
                                    </div>
                                </div>

                                <!-- Month Selection -->
                                <div id="monthSelectionWrapper" style="display: none;">
                                    <h6 class="mb-2">Pilih Bulan Mulai:</h6>
                                    <div id="monthSelection" class="row g-2">
                                        <!-- Months will be populated here -->
                                    </div>
                                </div>

                                <!-- Selected Info -->
                                <div id="selectedInfo" class="mt-3 p-2 bg-info text-white rounded small"
                                    style="display: none;">
                                    <strong>Dataset dipilih:</strong> <span id="selectedText"></span><br>
                                    <span id="dataCountText"></span>
                                </div>

                                <!-- Reset Button -->
                                <div class="mt-2">
                                    <button type="button" class="btn btn-sm btn-secondary"
                                        onclick="resetSelection()">Reset Pilihan</button>
                                </div>
                            </div>

                            <div id="noBarangSelected" class="text-center text-muted py-4">
                                <i class="fas fa-info-circle"></i><br>
                                Pilih barang terlebih dahulu untuk melihat data yang tersedia
                            </div>
                        </div>

                        <!-- Hidden inputs for form submission -->
                        <input type="hidden" name="tahun_dataset" id="selectedYear">
                        <input type="hidden" name="bulan_dataset" id="selectedMonth">

                        @error('tahun_dataset')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                        @error('bulan_dataset')
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
                            - Pastikan barang yang dipilih memiliki data penjualan minimal 5 bulan<br>
                            - Hasil prediksi akan menampilkan nilai MAPE (Mean Absolute Percentage Error)<br>
                            - Pilih bulan mulai untuk menggunakan data dari bulan tersebut hingga data terbaru
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
    .year-btn,
    .month-btn {
        min-width: 60px;
        font-size: 14px;
    }

    .month-btn {
        min-width: 50px;
    }

    .year-btn.selected,
    .month-btn.selected {
        background-color: #6f42c1 !important;
        border-color: #6f42c1 !important;
    }

    .month-btn:disabled {
        background-color: #e9ecef;
        border-color: #dee2e6;
        color: #6c757d;
        text-decoration: line-through;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px;
    }
</style>

<script>
    let availableData = [];
    let selectedYear = null;
    let selectedMonth = null;

    document.getElementById('barang_id').addEventListener('change', function() {
        const barangId = this.value;

        if (!barangId) {
            document.getElementById('dateSelectionArea').style.display = 'none';
            document.getElementById('noBarangSelected').style.display = 'block';
            document.getElementById('submitBtn').disabled = true;
            resetSelection();
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
                console.log('Data received:', data); // Debug log
                availableData = data;
                displayYearSelection();
                document.getElementById('dateSelectionArea').style.display = 'block';
                document.getElementById('noBarangSelected').style.display = 'none';
                resetSelection();
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('noBarangSelected').innerHTML =
                    `<i class="fas fa-exclamation-triangle text-warning"></i><br>Error memuat data: ${error.message}`;
            });
    });

    function displayYearSelection() {
        const yearContainer = document.getElementById('yearSelection');
        yearContainer.innerHTML = '';

        availableData.forEach(yearData => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'btn btn-outline-primary year-btn';
            button.textContent = yearData.year;
            button.onclick = () => selectYear(yearData.year);
            yearContainer.appendChild(button);
        });
    }

    function selectYear(year) {
        selectedYear = year;
        selectedMonth = null;

        // Update year button states
        document.querySelectorAll('.year-btn').forEach(btn => {
            btn.classList.toggle('selected', btn.textContent == year);
        });

        // Display month selection
        displayMonthSelection(year);
        document.getElementById('monthSelectionWrapper').style.display = 'block';
        updateSelectedInfo();
    }

    function displayMonthSelection(year) {
        const monthContainer = document.getElementById('monthSelection');
        monthContainer.innerHTML = '';

        const yearData = availableData.find(data => data.year == year);
        if (!yearData) return;

        yearData.months.forEach(monthData => {
            const colDiv = document.createElement('div');
            colDiv.className = 'col-3';

            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'btn btn-outline-secondary month-btn w-100';
            button.textContent = monthData.name;
            button.disabled = !monthData.available;

            if (monthData.available) {
                button.onclick = () => selectMonth(monthData.number);
            }

            colDiv.appendChild(button);
            monthContainer.appendChild(colDiv);
        });
    }

    function selectMonth(month) {
        selectedMonth = month;

        // Update month button states
        document.querySelectorAll('.month-btn:not(:disabled)').forEach(btn => {
            btn.classList.remove('selected');
        });

        event.target.classList.add('selected');
        updateSelectedInfo();
    }

    function updateSelectedInfo() {
        const selectedInfo = document.getElementById('selectedInfo');
        const selectedText = document.getElementById('selectedText');
        const dataCountText = document.getElementById('dataCountText');
        const submitBtn = document.getElementById('submitBtn');

        // Update hidden inputs
        document.getElementById('selectedYear').value = selectedYear || '';
        document.getElementById('selectedMonth').value = selectedMonth || '';

        if (selectedYear) {
            let text = `Tahun ${selectedYear}`;
            if (selectedMonth) {
                const monthNames = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                ];
                text += ` mulai dari ${monthNames[selectedMonth]}`;
            }
            selectedText.textContent = text;

            // Count available data
            const yearData = availableData.find(data => data.year == selectedYear);
            let monthCount = 0;
            if (yearData) {
                if (selectedMonth) {
                    monthCount = yearData.months.filter(m => m.number >= selectedMonth && m.available).length;
                } else {
                    monthCount = yearData.months.filter(m => m.available).length;
                }
            }

            dataCountText.textContent =
                `Tersedia ${monthCount} bulan data ${monthCount >= 5 ? '✓' : '(minimal 5 bulan)'}`;
            selectedInfo.style.display = 'block';

            // Enable submit if enough data
            submitBtn.disabled = monthCount < 5;
        } else {
            selectedInfo.style.display = 'none';
            submitBtn.disabled = true;
        }
    }

    function resetSelection() {
        selectedYear = null;
        selectedMonth = null;

        document.querySelectorAll('.year-btn').forEach(btn => btn.classList.remove('selected'));
        document.querySelectorAll('.month-btn').forEach(btn => btn.classList.remove('selected'));
        document.getElementById('monthSelectionWrapper').style.display = 'none';
        document.getElementById('selectedInfo').style.display = 'none';
        document.getElementById('selectedYear').value = '';
        document.getElementById('selectedMonth').value = '';
        document.getElementById('submitBtn').disabled = true;
    }
</script>
