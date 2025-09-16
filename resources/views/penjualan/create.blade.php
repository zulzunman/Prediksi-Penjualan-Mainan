<!-- Create Penjualan Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('penjualan.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="createModalLabel">
                        <i class="bi bi-plus-circle me-2"></i>
                        Tambah Penjualan Baru
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
                            <select class="form-select @error('barang_id') is-invalid @enderror" id="barang_id"
                                name="barang_id" required>
                                <option value="">-- Pilih Barang --</option>
                                @foreach ($barang as $item)
                                    <option value="{{ $item->id }}" data-harga="{{ $item->harga }}"
                                        data-stok="{{ $item->stok }}"
                                        {{ old('barang_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama_barang }} - Stok: {{ $item->stok }} unit - Rp
                                        {{ number_format($item->harga, 0, ',', '.') }}
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

                        <!-- Info Barang (Read-only) -->
                        <div class="col-md-4">
                            <label for="stok_tersedia" class="form-label fw-semibold">
                                <i class="bi bi-stack text-info me-1"></i>
                                Stok Tersedia
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control bg-light" id="stok_tersedia" readonly>
                                <span class="input-group-text">unit</span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="harga_satuan_display" class="form-label fw-semibold">
                                <i class="bi bi-currency-dollar text-warning me-1"></i>
                                Harga Satuan
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control bg-light" id="harga_satuan_display" readonly>
                                <input type="hidden" id="harga_satuan" name="harga_satuan">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="tanggal" class="form-label fw-semibold">
                                <i class="bi bi-calendar text-primary me-1"></i>
                                Tanggal Penjualan <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control @error('tanggal') is-invalid @enderror"
                                id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="invalid-feedback">
                                Tanggal penjualan harus diisi
                            </div>
                        </div>

                        <!-- Jumlah Penjualan -->
                        <div class="col-12">
                            <label for="jumlah_penjualan" class="form-label fw-semibold">
                                <i class="bi bi-hash text-success me-1"></i>
                                Jumlah Penjualan <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number"
                                    class="form-control @error('jumlah_penjualan') is-invalid @enderror"
                                    id="jumlah_penjualan" name="jumlah_penjualan" value="{{ old('jumlah_penjualan') }}"
                                    placeholder="0" min="1" required>
                                <span class="input-group-text">unit</span>
                                @error('jumlah_penjualan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="invalid-feedback">
                                    Jumlah penjualan harus berupa angka minimal 1
                                </div>
                            </div>
                            <small class="text-muted">Maksimal: <span id="max-stok">0</span> unit</small>
                        </div>

                        <!-- Preview Card -->
                        <div class="col-12">
                            <div class="card bg-light border-0">
                                <div class="card-header bg-transparent border-0 pb-0">
                                    <h6 class="mb-0">
                                        <i class="bi bi-eye text-info me-1"></i>
                                        Preview Penjualan
                                    </h6>
                                </div>
                                <div class="card-body pt-2">
                                    <div class="row text-center">
                                        <div class="col-3">
                                            <small class="text-muted d-block">Barang</small>
                                            <span class="fw-semibold" id="preview-nama">-</span>
                                        </div>
                                        <div class="col-3">
                                            <small class="text-muted d-block">Jumlah</small>
                                            <span class="fw-semibold" id="preview-jumlah">-</span>
                                        </div>
                                        <div class="col-3">
                                            <small class="text-muted d-block">Harga</small>
                                            <span class="fw-semibold" id="preview-harga">-</span>
                                        </div>
                                        <div class="col-3">
                                            <small class="text-muted d-block">Stok Sisa</small>
                                            <span class="fw-semibold" id="preview-stok">-</span>
                                        </div>
                                    </div>
                                    <hr class="my-2">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <small class="text-muted">Total Penjualan:</small>
                                            <div class="fw-bold text-success fs-5" id="preview-total">Rp 0</div>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Tanggal:</small>
                                            <div class="fw-semibold" id="preview-tanggal">-</div>
                                        </div>
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
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>
                        Simpan Penjualan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const barangSelect = document.getElementById('barang_id');
        const jumlahInput = document.getElementById('jumlah_penjualan');
        const stokDisplay = document.getElementById('stok_tersedia');
        const hargaDisplay = document.getElementById('harga_satuan_display');
        const hargaHidden = document.getElementById('harga_satuan');
        const maxStokSpan = document.getElementById('max-stok');

        // Function untuk update data barang
        const updateBarangData = () => {
            const selectedOption = barangSelect.options[barangSelect.selectedIndex];

            if (selectedOption.value) {
                const harga = selectedOption.getAttribute('data-harga');
                const stok = selectedOption.getAttribute('data-stok');

                // Update displays
                stokDisplay.value = stok;
                hargaDisplay.value = parseInt(harga).toLocaleString('id-ID');
                hargaHidden.value = harga;
                maxStokSpan.textContent = stok;

                // Set max untuk input jumlah
                jumlahInput.max = stok;

                // Reset jumlah jika melebihi stok
                if (parseInt(jumlahInput.value) > parseInt(stok)) {
                    jumlahInput.value = '';
                }
            } else {
                stokDisplay.value = '';
                hargaDisplay.value = '';
                hargaHidden.value = '';
                maxStokSpan.textContent = '0';
                jumlahInput.max = '';
            }

            updatePreview();
        };

        // Function untuk update preview
        const updatePreview = () => {
            const selectedOption = barangSelect.options[barangSelect.selectedIndex];
            const nama = selectedOption.value ? selectedOption.text.split(' - ')[0] : '-';
            const jumlah = jumlahInput.value || '0';
            const harga = hargaHidden.value || '0';
            const stokTersedia = stokDisplay.value || '0';
            const tanggal = document.getElementById('tanggal').value || '-';

            // Hitung stok sisa setelah penjualan
            const stokSisa = parseInt(stokTersedia) - parseInt(jumlah || 0);

            document.getElementById('preview-nama').textContent = nama;
            document.getElementById('preview-jumlah').textContent = jumlah + ' unit';
            document.getElementById('preview-harga').textContent = 'Rp ' + parseInt(harga || 0)
                .toLocaleString('id-ID');
            document.getElementById('preview-stok').textContent = (stokSisa >= 0 ? stokSisa :
                stokTersedia) + ' unit';

            const total = parseInt(jumlah || 0) * parseInt(harga || 0);
            document.getElementById('preview-total').textContent = 'Rp ' + total.toLocaleString('id-ID');

            // Format tanggal
            if (tanggal && tanggal !== '-') {
                const dateObj = new Date(tanggal);
                const formattedDate = dateObj.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
                document.getElementById('preview-tanggal').textContent = formattedDate;
            } else {
                document.getElementById('preview-tanggal').textContent = '-';
            }
        };

        // Event listeners
        barangSelect.addEventListener('change', updateBarangData);
        jumlahInput.addEventListener('input', function() {
            const maxStok = parseInt(this.max);
            if (parseInt(this.value) > maxStok) {
                this.setCustomValidity(`Jumlah tidak boleh melebihi stok tersedia (${maxStok} unit)`);
            } else {
                this.setCustomValidity('');
            }
            updatePreview();
        });
        document.getElementById('tanggal').addEventListener('change', updatePreview);

        // Reset form when modal is hidden
        document.getElementById('createModal').addEventListener('hidden.bs.modal', function() {
            this.querySelector('form').reset();
            this.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.getElementById('tanggal').value = new Date().toISOString().split('T')[0];
            stokDisplay.value = '';
            hargaDisplay.value = '';
            hargaHidden.value = '';
            maxStokSpan.textContent = '0';
            updatePreview();
        });

        // Initialize
        updateBarangData();
        updatePreview();
    });
</script>
