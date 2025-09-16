<!-- Edit Barang Modal -->
<div class="modal fade" id="editBarangModal{{ $barang->id }}" tabindex="-1"
    aria-labelledby="editBarangModalLabel{{ $barang->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('barang.update', $barang->id) }}" method="POST" class="needs-validation" novalidate>
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="editBarangModalLabel{{ $barang->id }}">
                        <i class="bi bi-pencil-square me-2"></i>
                        Edit Barang: {{ $barang->nama_barang }}
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
                                        ID Barang: <strong>{{ $barang->id }}</strong> |
                                        Dibuat:
                                        {{ $barang->created_at ? $barang->created_at->format('d/m/Y H:i') : '-' }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Nama Barang -->
                        <div class="col-12">
                            <label for="edit_nama_barang{{ $barang->id }}" class="form-label fw-semibold">
                                <i class="bi bi-box text-primary me-1"></i>
                                Nama Barang <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('nama_barang') is-invalid @enderror"
                                id="edit_nama_barang{{ $barang->id }}" name="nama_barang"
                                value="{{ old('nama_barang', $barang->nama_barang) }}"
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
                                                Data Lama
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-2">
                                                <small class="text-muted">Stok:</small>
                                                <div class="fw-semibold">{{ $barang->stok }} unit</div>
                                            </div>
                                            <div class="mb-2">
                                                <small class="text-muted">Harga:</small>
                                                <div class="fw-semibold">Rp {{ number_format($barang->harga) }}</div>
                                            </div>
                                            <div>
                                                <small class="text-muted">Total:</small>
                                                <div class="fw-semibold text-success">Rp
                                                    {{ number_format($barang->harga * $barang->stok) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <!-- Stok dan Harga Baru -->
                                    <div class="mb-3">
                                        <label for="edit_stok{{ $barang->id }}" class="form-label fw-semibold">
                                            <i class="bi bi-stack text-success me-1"></i>
                                            Stok Baru <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="number"
                                                class="form-control @error('stok') is-invalid @enderror"
                                                id="edit_stok{{ $barang->id }}" name="stok"
                                                value="{{ old('stok', $barang->stok) }}" placeholder="0" min="0"
                                                required>
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
                                        <label for="edit_harga{{ $barang->id }}" class="form-label fw-semibold">
                                            <i class="bi bi-currency-dollar text-warning me-1"></i>
                                            Harga Baru <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number"
                                                class="form-control @error('harga') is-invalid @enderror"
                                                id="edit_harga{{ $barang->id }}" name="harga"
                                                value="{{ old('harga', $barang->harga) }}" placeholder="0"
                                                min="0" required>
                                            @error('harga')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="invalid-feedback">
                                                Harga harus berupa angka minimal 0
                                            </div>
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
                                    <div class="text-center">
                                        <div class="row">
                                            <div class="col-4">
                                                <small class="text-muted d-block">Nama</small>
                                                <span class="fw-semibold"
                                                    id="edit-preview-nama{{ $barang->id }}">{{ $barang->nama_barang }}</span>
                                            </div>
                                            <div class="col-4">
                                                <small class="text-muted d-block">Stok</small>
                                                <span class="fw-semibold"
                                                    id="edit-preview-stok{{ $barang->id }}">{{ $barang->stok }}
                                                    unit</span>
                                            </div>
                                            <div class="col-4">
                                                <small class="text-muted d-block">Harga</small>
                                                <span class="fw-semibold"
                                                    id="edit-preview-harga{{ $barang->id }}">Rp
                                                    {{ number_format($barang->harga) }}</span>
                                            </div>
                                        </div>
                                        <hr class="my-2">
                                        <div>
                                            <small class="text-muted">Nilai Total Baru: </small>
                                            <span class="fw-bold text-primary"
                                                id="edit-preview-total{{ $barang->id }}">Rp
                                                {{ number_format($barang->harga * $barang->stok) }}</span>
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
                        Update Barang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Preview functionality untuk edit modal {{ $barang->id }}
    document.addEventListener('DOMContentLoaded', function() {
        const updateEditPreview{{ $barang->id }} = () => {
            const nama = document.getElementById('edit_nama_barang{{ $barang->id }}').value || '-';
            const stok = document.getElementById('edit_stok{{ $barang->id }}').value || '0';
            const harga = document.getElementById('edit_harga{{ $barang->id }}').value || '0';

            document.getElementById('edit-preview-nama{{ $barang->id }}').textContent = nama;
            document.getElementById('edit-preview-stok{{ $barang->id }}').textContent = stok + ' unit';
            document.getElementById('edit-preview-harga{{ $barang->id }}').textContent = 'Rp ' +
                parseInt(harga).toLocaleString('id-ID');

            const total = parseInt(stok || 0) * parseInt(harga || 0);
            document.getElementById('edit-preview-total{{ $barang->id }}').textContent = 'Rp ' + total
                .toLocaleString('id-ID');
        };

        document.getElementById('edit_nama_barang{{ $barang->id }}').addEventListener('input',
            updateEditPreview{{ $barang->id }});
        document.getElementById('edit_stok{{ $barang->id }}').addEventListener('input',
            updateEditPreview{{ $barang->id }});
        document.getElementById('edit_harga{{ $barang->id }}').addEventListener('input',
            updateEditPreview{{ $barang->id }});
    });
</script>
