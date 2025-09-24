<!-- Modal Show Detail Prediksi -->
<div class="modal fade" id="showPrediksiModal{{ $prediksi->id }}" tabindex="-1"
    aria-labelledby="showPrediksiModalLabel{{ $prediksi->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showPrediksiModalLabel{{ $prediksi->id }}">
                    Detail Prediksi - {{ $prediksi->barang->nama_barang ?? 'N/A' }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Informasi Prediksi</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <tr>
                                        <th style="width: 40%">Barang:</th>
                                        <td>{{ $prediksi->barang->nama_barang ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Metode:</th>
                                        <td>{{ $prediksi->metode }}</td>
                                    </tr>
                                    <tr>
                                        <th>Periode:</th>
                                        <td>{{ $prediksi->periode }} bulan</td>
                                    </tr>
                                    <tr>
                                        <th>MAPE:</th>
                                        <td>
                                            <span
                                                class="badge {{ $prediksi->mape <= 10 ? 'bg-success' : ($prediksi->mape <= 20 ? 'bg-warning' : 'bg-danger') }}">
                                                {{ $prediksi->mape }}%
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Dibuat:</th>
                                        <td>{{ $prediksi->created_at->format('d-m-Y H:i:s') }}</td>
                                    </tr>
                                </table>

                                <div class="mt-3">
                                    <small class="text-muted">
                                        <strong>Interpretasi MAPE:</strong><br>
                                        • ≤ 10%: Sangat Baik<br>
                                        • 11-20%: Baik<br>
                                        • 21-50%: Cukup<br>
                                        • > 50%: Kurang Akurat
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Hasil Prediksi</h6>
                            </div>
                            <div class="card-body">
                                @php
                                    // Normalisasi hasil_prediksi
                                    $raw = $prediksi->hasil_prediksi;
                                    $items = [];

                                    if (
                                        is_array($raw) &&
                                        isset($raw[0]) &&
                                        is_array($raw[0]) &&
                                        array_key_exists('label', $raw[0])
                                    ) {
                                        // format baru: array of arrays
                                        $items = $raw;
                                    } elseif (is_array($raw)) {
                                        // format lama: assoc label => value
                                        foreach ($raw as $lbl => $val) {
                                            $items[] = ['label' => $lbl, 'nilai' => $val];
                                        }
                                    }
                                @endphp

                                @if (count($items) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Periode</th>
                                                    <th>Prediksi Penjualan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($items as $index => $item)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>
                                                            <strong>{{ $item['label'] }}</strong>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-primary">
                                                                {{ number_format($item['nilai']) }} unit
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr class="table-info">
                                                    <th colspan="2">Total Prediksi:</th>
                                                    <th>
                                                        <span class="badge bg-info">
                                                            {{ number_format(collect($items)->sum('nilai')) }} unit
                                                        </span>
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Tidak ada data prediksi yang tersedia.
                                    </div>
                                @endif

                                @if (count($items) > 0)
                                    <div class="mt-3">
                                        <h6>Ringkasan Prediksi:</h6>
                                        <ul class="list-unstyled">
                                            <li><strong>Prediksi Tertinggi:</strong>
                                                @php $maxItem = collect($items)->sortByDesc('nilai')->first(); @endphp
                                                {{ $maxItem['label'] ?? 'N/A' }}
                                                ({{ number_format($maxItem['nilai'] ?? 0) }} unit)
                                            </li>
                                            <li><strong>Prediksi Terendah:</strong>
                                                @php $minItem = collect($items)->sortBy('nilai')->first(); @endphp
                                                {{ $minItem['label'] ?? 'N/A' }}
                                                ({{ number_format($minItem['nilai'] ?? 0) }} unit)
                                            </li>
                                            <li><strong>Rata-rata Prediksi:</strong>
                                                {{ number_format(collect($items)->avg('nilai')) }} unit/bulan
                                            </li>
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if ($prediksi->barang)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Informasi Barang</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong>Nama Barang:</strong><br>
                                            {{ $prediksi->barang->nama_barang }}
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Kategori:</strong><br>
                                            {{ $prediksi->barang->kategori ?? 'N/A' }}
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Harga Satuan:</strong><br>
                                            Rp {{ number_format($prediksi->barang->harga ?? 0, 0, ',', '.') }}
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Stok Tersedia:</strong><br>
                                            {{ number_format($prediksi->barang->stok ?? 0) }} unit
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="window.print()">
                    <i class="fas fa-print"></i> Print Detail
                </button>
            </div>
        </div>
    </div>
</div>
