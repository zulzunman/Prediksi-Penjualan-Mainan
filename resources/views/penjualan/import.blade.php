{{-- resources/views/penjualan/import.blade.php --}}
<div class="modal fade" id="importPenjualanModal" tabindex="-1" aria-labelledby="importPenjualanModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="importPenjualanModalLabel">
                    <i class="bi bi-cloud-upload me-2"></i>
                    Import Data Penjualan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('penjualan.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                @csrf
                <!-- Hidden fields to maintain current filter state -->
                <input type="hidden" name="bulan" value="{{ $bulan }}">
                <input type="hidden" name="tahun" value="{{ $tahun }}">
                <input type="hidden" name="barang_filter" value="{{ $barang_filter }}">
                <input type="hidden" name="tab" value="{{ $activeTab }}">

                <div class="modal-body">
                    {{-- Instructions --}}
                    <div class="alert alert-info d-flex align-items-start">
                        <i class="bi bi-info-circle fs-5 me-3 mt-1"></i>
                        <div>
                            <h6 class="alert-heading mb-2">Petunjuk Import:</h6>
                            <ul class="mb-0 small">
                                <li>Download template Excel terlebih dahulu</li>
                                <li>Isi data sesuai format yang tersedia</li>
                                <li>Pastikan nama barang sesuai dengan data yang ada di sistem</li>
                                <li>Format tanggal: YYYY-MM-DD atau DD/MM/YYYY</li>
                                <li>File yang didukung: .xlsx, .xls, .csv (maksimal 2MB)</li>
                            </ul>
                        </div>
                    </div>

                    {{-- Download Template Button --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-download me-2"></i>
                            Download Template
                        </label>
                        <div>
                            <a href="{{ route('penjualan.template') }}" class="btn btn-outline-success" target="_blank">
                                <i class="bi bi-file-earmark-excel me-2"></i>
                                Download Template Excel
                            </a>
                            <small class="text-muted d-block mt-1">
                                Template berisi contoh data dan format yang benar
                            </small>
                        </div>
                    </div>

                    {{-- File Upload --}}
                    <div class="mb-3">
                        <label for="excel_file" class="form-label fw-semibold required">
                            <i class="bi bi-cloud-upload me-2"></i>
                            Pilih File Excel
                        </label>
                        <input type="file" class="form-control @error('excel_file') is-invalid @enderror"
                            id="excel_file" name="excel_file" accept=".xlsx,.xls,.csv" required>
                        @error('excel_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            Pilih file Excel/CSV yang berisi data penjualan (maks. 2MB)
                        </div>
                    </div>

                    {{-- File Preview --}}
                    <div id="filePreview" class="d-none">
                        <div class="card bg-light">
                            <div class="card-body py-2">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-file-earmark-excel text-success fs-4 me-3"></i>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold" id="fileName"></div>
                                        <small class="text-muted" id="fileSize"></small>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger" id="removeFile">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Warning --}}
                    <div class="alert alert-warning d-flex align-items-start mt-3">
                        <i class="bi bi-exclamation-triangle fs-5 me-3 mt-1"></i>
                        <div>
                            <strong>Perhatian:</strong>
                            <ul class="mb-0 small">
                                <li>Import akan mengurangi stok barang secara otomatis</li>
                                <li>Pastikan data sudah benar sebelum mengimpor</li>
                                <li>Proses import tidak dapat dibatalkan</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary" id="importBtn" disabled>
                        <i class="bi bi-cloud-upload me-2"></i>
                        <span class="btn-text">Import Data</span>
                        <span class="spinner-border spinner-border-sm ms-2 d-none" role="status"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .required::after {
        content: " *";
        color: #dc3545;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('excel_file');
        const filePreview = document.getElementById('filePreview');
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');
        const removeFileBtn = document.getElementById('removeFile');
        const importBtn = document.getElementById('importBtn');
        const importForm = document.getElementById('importForm');

        // File input change handler
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];

            if (file) {
                // Show file preview
                fileName.textContent = file.name;
                fileSize.textContent = formatFileSize(file.size);
                filePreview.classList.remove('d-none');
                importBtn.disabled = false;

                // Validate file type
                const allowedTypes = ['.xlsx', '.xls', '.csv'];
                const fileExtension = '.' + file.name.split('.').pop().toLowerCase();

                if (!allowedTypes.includes(fileExtension)) {
                    alert('File type tidak didukung. Gunakan file .xlsx, .xls, atau .csv');
                    resetFileInput();
                    return;
                }

                // Validate file size (2MB = 2097152 bytes)
                if (file.size > 2097152) {
                    alert('Ukuran file terlalu besar. Maksimal 2MB');
                    resetFileInput();
                    return;
                }
            } else {
                resetFileInput();
            }
        });

        // Remove file handler
        removeFileBtn.addEventListener('click', function() {
            resetFileInput();
        });

        // Form submit handler
        importForm.addEventListener('submit', function(e) {
            const file = fileInput.files[0];
            if (!file) {
                e.preventDefault();
                alert('Silakan pilih file Excel terlebih dahulu');
                return;
            }

            // Show loading state
            importBtn.disabled = true;
            importBtn.querySelector('.btn-text').textContent = 'Mengimpor...';
            importBtn.querySelector('.spinner-border').classList.remove('d-none');
        });

        function resetFileInput() {
            fileInput.value = '';
            filePreview.classList.add('d-none');
            importBtn.disabled = true;
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Reset form when modal is hidden
        document.getElementById('importPenjualanModal').addEventListener('hidden.bs.modal', function() {
            resetFileInput();
            importBtn.querySelector('.btn-text').textContent = 'Import Data';
            importBtn.querySelector('.spinner-border').classList.add('d-none');
            importBtn.disabled = true;
        });
    });
</script>
