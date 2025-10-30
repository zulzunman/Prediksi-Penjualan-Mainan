<!-- Edit Penjualan Modal -->
<div class="modal fade" id="editPenjualanModal{{ $penjualan->id }}" tabindex="-1"
    aria-labelledby="editPenjualanModalLabel{{ $penjualan->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('penjualan.update', $penjualan->id) }}" method="POST" class="needs-validation"
                novalidate>
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="editPenjualanModalLabel{{ $penjualan->id }}">
                        <i class="bi bi-pencil-square me-2"></i>
                        Edit Penjualan: {{ $penjualan->nama_barang }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Info Card -->
                        <div class="col-12">
                            <div class="card bg-info bg-opacity-10 border-info border-opacity-25">
                                <div class="card-body py-2">
                                    <small class="text-info">
                                        <i class="bi bi-info-circle me-1"></i>
                                        ID Transaksi: <strong>{{ $penjualan->id }}</strong> |
                                        Total Lama: <strong>Rp {{ number_format($penjualan->total_harga) }}</strong> |
                                        Dibuat:
                                        {{ $penjualan->created_at ? $penjualan->created_at->format('d/m/Y H:i') : '-' }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Nama Barang -->
                        <div class="col-12">
                            <label for="edit_nama_barang{{ $penjualan->id }}" class="form-label fw-semibold">
                                <i class="bi bi-box text-primary me-1"></i>
                                Nama Barang <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('nama_barang') is-invalid @enderror"
                                id="edit_nama_barang{{ $penjualan->id }}" name="nama_barang"
                                value="{{ old('nama_barang', $penjualan->nama_barang) }}"
                                placeholder="Masukkan nama barang" required>
                            @error('nama_barang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="invalid-feedback">
                                Nama barang harus diisi
                            </div>
                        </div>

                        <!-- Data Lama vs Baru -->
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card bg-light border-0 h-100">
                                        <div class="card-header bg-secondary text-white py-2">
                                            <h6 class="mb-0">
                                                <i class="bi bi-clock-history me-1"></i>
                                                Data Penjualan Lama
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-2">
                                                <small class="text-muted">Jumlah:</small>
                                                <div class="fw-semibold">{{ $penjualan->jumlah_penjualan }} unit</div>
                                            </div>
                                            <div class="mb-2">
                                                <small class="text-muted">Harga Satuan:</small>
                                                <div class="fw-semibold">Rp
                                                    {{ number_format($penjualan->harga_satuan) }}</div>
                                            </div>
                                            <div class="mb-2">
                                                <small class="text-muted">Stok:</small>
                                                <div class="fw-semibold">{{ $penjualan->stok }} unit</div>
                                            </div>
                                            <div class="mb-2">
                                                <small class="text-muted">Tanggal:</small>
                                                <div class="fw-semibold">
                                                    {{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d/m/Y') }}
                                                </div>
                                            </div>
                                            <hr class="my-2">
                                            <div>
                                                <small class="text-muted">Total Lama:</small>
                                                <div class="fw-semibold text-success">Rp
                                                    {{ number_format($penjualan->total_harga) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <!-- Data Baru -->
                                    <div class="mb-3">
                                        <label for="edit_jumlah_penjualan{{ $penjualan->id }}"
                                            class="form-label fw-semibold">
                                            <i class="bi bi-hash text-success me-1"></i>
                                            Jumlah Baru <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="number"
                                                class="form-control @error('jumlah_penjualan') is-invalid @enderror"
                                                id="edit_jumlah_penjualan{{ $penjualan->id }}" name="jumlah_penjualan"
                                                value="{{ old('jumlah_penjualan', $penjualan->jumlah_penjualan) }}"
                                                placeholder="0" min="1" required>
                                            <span class="input-group-text">unit</span>
                                            @error('jumlah_penjualan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="invalid-feedback">
                                                Jumlah harus berupa angka minimal 1
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit_harga_satuan{{ $penjualan->id }}"
                                            class="form-label fw-semibold">
                                            <i class="bi bi-currency-dollar text-warning me-1"></i>
                                            Harga Satuan Baru <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number"
                                                class="form-control @error('harga_satuan') is-invalid @enderror"
                                                id="edit_harga_satuan{{ $penjualan->id }}" name="harga_satuan"
                                                value="{{ old('harga_satuan', $penjualan->harga_satuan) }}"
                                                placeholder="0" min="0" step="0.01" required>
                                            @error('harga_satuan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="invalid-feedback">
                                                Harga harus berupa angka minimal 0
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit_stok{{ $penjualan->id }}" class="form-label fw-semibold">
                                            <i class="bi bi-stack text-info me-1"></i>
                                            Stok Baru <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="number"
                                                class="form-control @error('stok') is-invalid @enderror"
                                                id="edit_stok{{ $penjualan->id }}" name="stok"
                                                value="{{ old('stok', $penjualan->stok) }}" placeholder="0"
                                                min="0" required>
                                            <span class="input-group-text">unit</span>
                                            @error('stok')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="invalid-feedback">
                                                Stok harus berupa angka minimal 0
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit_tanggal{{ $penjualan->id }}" class="form-label fw-semibold">
                                            <i class="bi bi-calendar text-primary me-1"></i>
                                            Tanggal Baru <span class="text-danger">*</span>
                                        </label>
                                        <input type="date"
                                            class="form-control @error('tanggal') is-invalid @enderror"
                                            id="edit_tanggal{{ $penjualan->id }}" name="tanggal"
                                            value="{{ old('tanggal', $penjualan->tanggal) }}" required>
                                        @error('tanggal')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="invalid-feedback">
                                            Tanggal harus diisi
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Preview Perubahan -->
                        <div class="col-12">
                            <div class="card bg-primary bg-opacity-10 border-primary border-opacity-25">
                                <div class="card-header bg-transparent border-0 pb-0">
                                    <h6 class="mb-0 text-primary">
                                        <i class="bi bi-arrow-repeat me-1"></i>
                                        Preview Perubahan
                                    </h6>
                                </div>
                                <div class="card-body pt-2">
                                    <div class="row text-center mb-3">
                                        <div class="col-3">
                                            <small class="text-muted d-block">Barang</small>
                                            <span class="fw-semibold"
                                                id="edit-preview-nama{{ $penjualan->id }}">{{ $penjualan->nama_barang }}</span>
                                        </div>
                                        <div class="col-3">
                                            <small class="text-muted d-block">Jumlah</small>
                                            <span class="fw-semibold"
                                                id="edit-preview-jumlah{{ $penjualan->id }}">{{ $penjualan->jumlah_penjualan }}
                                                unit</span>
                                        </div>
                                        <div class="col-3">
                                            <small class="text-muted d-block">Harga</small>
                                            <span class="fw-semibold" id="edit-preview-harga{{ $penjualan->id }}">Rp
                                                {{ number_format($penjualan->harga_satuan) }}</span>
                                        </div>
                                        <div class="col-3">
                                            <small class="text-muted d-block">Stok</small>
                                            <span class="fw-semibold"
                                                id="edit-preview-stok{{ $penjualan->id }}">{{ $penjualan->stok }}
                                                unit</span>
                                        </div>
                                    </div>
                                    <hr class="my-2">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <small class="text-muted">Total Penjualan Baru:</small>
                                            <div class="fw-bold text-primary fs-5"
                                                id="edit-preview-total{{ $penjualan->id }}">Rp
                                                {{ number_format($penjualan->total_harga) }}</div>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Tanggal Baru:</small>
                                            <div class="fw-semibold" id="edit-preview-tanggal{{ $penjualan->id }}">
                                                {{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d F Y') }}</div>
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
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-check-circle me-1"></i>
                        Update Penjualan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Preview functionality untuk edit modal {{ $penjualan->id }}
    document.addEventListener('DOMContentLoaded', function() {
        const updateEditPreview{{ $penjualan->id }} = () => {
            const nama = document.getElementById('edit_nama_barang{{ $penjualan->id }}').value || '-';
            const jumlah = document.getElementById('edit_jumlah_penjualan{{ $penjualan->id }}').value ||
                '0';
            const harga = document.getElementById('edit_harga_satuan{{ $penjualan->id }}').value || '0';
            const stok = document.getElementById('edit_stok{{ $penjualan->id }}').value || '0';
            const tanggal = document.getElementById('edit_tanggal{{ $penjualan->id }}').value || '';

            document.getElementById('edit-preview-nama{{ $penjualan->id }}').textContent = nama;
            document.getElementById('edit-preview-jumlah{{ $penjualan->id }}').textContent = jumlah +
                ' unit';
            document.getElementById('edit-preview-harga{{ $penjualan->id }}').textContent = 'Rp ' +
                parseInt(harga || 0).toLocaleString('id-ID');
            document.getElementById('edit-preview-stok{{ $penjualan->id }}').textContent = stok + ' unit';

            const total = parseInt(jumlah || 0) * parseInt(harga || 0);
            document.getElementById('edit-preview-total{{ $penjualan->id }}').textContent = 'Rp ' + total
                .toLocaleString('id-ID');

            // Format tanggal
            if (tanggal) {
                const dateObj = new Date(tanggal);
                const formattedDate = dateObj.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
                document.getElementById('edit-preview-tanggal{{ $penjualan->id }}').textContent =
                    formattedDate;
            } else {
                document.getElementById('edit-preview-tanggal{{ $penjualan->id }}').textContent = '-';
            }
        };

        document.getElementById('edit_nama_barang{{ $penjualan->id }}').addEventListener('input',
            updateEditPreview{{ $penjualan->id }});
        document.getElementById('edit_jumlah_penjualan{{ $penjualan->id }}').addEventListener('input',
            updateEditPreview{{ $penjualan->id }});
        document.getElementById('edit_harga_satuan{{ $penjualan->id }}').addEventListener('input',
            updateEditPreview{{ $penjualan->id }});
        document.getElementById('edit_stok{{ $penjualan->id }}').addEventListener('input',
            updateEditPreview{{ $penjualan->id }});
        document.getElementById('edit_tanggal{{ $penjualan->id }}').addEventListener('change',
            updateEditPreview{{ $penjualan->id }});
    });
</script>
