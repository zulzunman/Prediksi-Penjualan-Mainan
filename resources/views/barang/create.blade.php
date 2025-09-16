<!-- Create Barang Modal -->
<div class="modal fade" id="createBarangModal" tabindex="-1" aria-labelledby="createBarangModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('barang.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="createBarangModalLabel">
                        <i class="bi bi-plus-circle me-2"></i>
                        Tambah Barang Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Nama Barang -->
                        <div class="col-12">
                            <label for="nama_barang" class="form-label fw-semibold">
                                <i class="bi bi-box text-primary me-1"></i>
                                Nama Barang <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('nama_barang') is-invalid @enderror"
                                id="nama_barang" name="nama_barang" value="{{ old('nama_barang') }}"
                                placeholder="Masukkan nama barang" required>
                            @error('nama_barang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="invalid-feedback">
                                Nama barang harus diisi
                            </div>
                        </div>

                        <!-- Stok dan Harga -->
                        <div class="col-md-6">
                            <label for="stok" class="form-label fw-semibold">
                                <i class="bi bi-stack text-success me-1"></i>
                                Stok <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('stok') is-invalid @enderror"
                                    id="stok" name="stok" value="{{ old('stok') }}" placeholder="0"
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

                        <div class="col-md-6">
                            <label for="harga" class="form-label fw-semibold">
                                <i class="bi bi-currency-dollar text-warning me-1"></i>
                                Harga <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('harga') is-invalid @enderror"
                                    id="harga" name="harga" value="{{ old('harga') }}" placeholder="0"
                                    min="0" required>
                                @error('harga')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="invalid-feedback">
                                    Harga harus berupa angka minimal 0
                                </div>
                            </div>
                        </div>

                        <!-- Preview Card -->
                        <div class="col-12">
                            <div class="card bg-light border-0">
                                <div class="card-header bg-transparent border-0 pb-0">
                                    <h6 class="mb-0">
                                        <i class="bi bi-eye text-info me-1"></i>
                                        Preview Data
                                    </h6>
                                </div>
                                <div class="card-body pt-2">
                                    <div class="row text-center">
                                        <div class="col-4">
                                            <small class="text-muted d-block">Nama Barang</small>
                                            <span class="fw-semibold" id="preview-nama">-</span>
                                        </div>
                                        <div class="col-4">
                                            <small class="text-muted d-block">Stok</small>
                                            <span class="fw-semibold" id="preview-stok">-</span>
                                        </div>
                                        <div class="col-4">
                                            <small class="text-muted d-block">Harga</small>
                                            <span class="fw-semibold" id="preview-harga">-</span>
                                        </div>
                                    </div>
                                    <hr class="my-2">
                                    <div class="text-center">
                                        <small class="text-muted">Nilai Total: </small>
                                        <span class="fw-bold text-success" id="preview-total">Rp 0</span>
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
                        Simpan Barang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Preview functionality untuk create modal
    document.addEventListener('DOMContentLoaded', function() {
        const updatePreview = () => {
            const nama = document.getElementById('nama_barang').value || '-';
            const stok = document.getElementById('stok').value || '0';
            const harga = document.getElementById('harga').value || '0';

            document.getElementById('preview-nama').textContent = nama;
            document.getElementById('preview-stok').textContent = stok + ' unit';
            document.getElementById('preview-harga').textContent = 'Rp ' + parseInt(harga).toLocaleString(
                'id-ID');

            const total = parseInt(stok || 0) * parseInt(harga || 0);
            document.getElementById('preview-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
        };

        document.getElementById('nama_barang').addEventListener('input', updatePreview);
        document.getElementById('stok').addEventListener('input', updatePreview);
        document.getElementById('harga').addEventListener('input', updatePreview);

        // Reset form when modal is hidden
        document.getElementById('createBarangModal').addEventListener('hidden.bs.modal', function() {
            this.querySelector('form').reset();
            this.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            updatePreview();
        });
    });
</script>
