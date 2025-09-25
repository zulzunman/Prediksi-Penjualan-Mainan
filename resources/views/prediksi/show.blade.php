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
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-info-circle me-2"></i>Informasi Prediksi
                                </h6>
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
                                        <th>Dataset Info:</th>
                                        <td><small class="text-muted">{{ $prediksi->dataset_info ?? 'N/A' }}</small>
                                        </td>
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
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-chart-line me-2"></i>Hasil Prediksi
                                </h6>
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
                                        $items = $raw;
                                    } elseif (is_array($raw)) {
                                        foreach ($raw as $lbl => $val) {
                                            $items[] = ['label' => $lbl, 'nilai' => $val];
                                        }
                                    }
                                @endphp

                                @if (count($items) > 0)
                                    <!-- Summary Cards untuk Prediksi -->
                                    <div class="row g-2 mb-3">
                                        @foreach ($items as $index => $item)
                                            <div class="col-12">
                                                <div class="card border-0 bg-primary-subtle border-primary">
                                                    <div
                                                        class="card-body p-2 d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <small
                                                                class="text-muted fw-semibold">{{ $item['label'] }}</small>
                                                        </div>
                                                        <div>
                                                            <span class="badge bg-primary">
                                                                {{ number_format($item['nilai']) }} unit
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Total Summary -->
                                    <div class="card border-success bg-success-subtle">
                                        <div class="card-body p-2 text-center">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="text-success fw-bold">
                                                        {{ number_format(collect($items)->sum('nilai')) }} unit
                                                    </div>
                                                    <small class="text-muted">Total Prediksi</small>
                                                </div>
                                                <div class="col-6">
                                                    <div class="text-info fw-bold">
                                                        {{ number_format(collect($items)->avg('nilai')) }} unit
                                                    </div>
                                                    <small class="text-muted">Rata-rata per Bulan</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Tidak ada data prediksi yang tersedia.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grafik Prediksi -->
                @if (count($items) > 0)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-chart-bar me-2"></i>
                                        Grafik Prediksi Penjualan
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="prediksiChart{{ $prediksi->id }}" height="80"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Script untuk Chart - Updated dengan style yang sama seperti index penjualan -->
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (count($items) > 0)
                // Data untuk chart prediksi {{ $prediksi->id }}
                const prediksiData{{ $prediksi->id }} = @json($items);

                // Event listener untuk modal
                const modal{{ $prediksi->id }} = document.getElementById('showPrediksiModal{{ $prediksi->id }}');
                if (modal{{ $prediksi->id }}) {
                    modal{{ $prediksi->id }}.addEventListener('shown.bs.modal', function() {
                        const ctx = document.getElementById('prediksiChart{{ $prediksi->id }}');
                        if (ctx && typeof Chart !== 'undefined') {

                            // Prepare chart data
                            const labels = prediksiData{{ $prediksi->id }}.map(item => item.label);
                            const values = prediksiData{{ $prediksi->id }}.map(item => item.nilai);

                            // Destroy existing chart if any
                            if (window.prediksiChartInstance{{ $prediksi->id }}) {
                                window.prediksiChartInstance{{ $prediksi->id }}.destroy();
                            }

                            // Create new chart dengan style yang sama seperti monthly chart
                            window.prediksiChartInstance{{ $prediksi->id }} = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                        label: 'Prediksi Penjualan (Unit)',
                                        data: values,
                                        backgroundColor: 'rgba(25, 135, 84, 0.7)', // Green color seperti di monthly chart
                                        borderColor: 'rgba(25, 135, 84, 1)',
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                        title: {
                                            display: true,
                                            text: 'Prediksi Penjualan - {{ $prediksi->barang->nama_barang ?? 'N/A' }}',
                                            font: {
                                                size: 14,
                                                weight: 'bold'
                                            }
                                        },
                                        legend: {
                                            display: true,
                                            position: 'top',
                                            labels: {
                                                usePointStyle: true,
                                                padding: 15
                                            }
                                        }
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            title: {
                                                display: true,
                                                text: 'Unit Terjual',
                                                font: {
                                                    size: 12,
                                                    weight: 'bold'
                                                }
                                            },
                                            ticks: {
                                                callback: function(value) {
                                                    return value.toLocaleString() + ' unit';
                                                },
                                                font: {
                                                    size: 11
                                                }
                                            },
                                            grid: {
                                                color: 'rgba(0,0,0,0.1)',
                                                borderDash: [5, 5]
                                            }
                                        },
                                        x: {
                                            title: {
                                                display: true,
                                                text: 'Periode',
                                                font: {
                                                    size: 12,
                                                    weight: 'bold'
                                                }
                                            },
                                            ticks: {
                                                font: {
                                                    size: 11
                                                }
                                            },
                                            grid: {
                                                display: false
                                            }
                                        }
                                    },
                                    interaction: {
                                        intersect: false,
                                        mode: 'index'
                                    },
                                    elements: {
                                        bar: {
                                            borderRadius: 4,
                                            borderSkipped: false,
                                        }
                                    },
                                    onHover: (event, activeElements) => {
                                        event.native.target.style.cursor = activeElements
                                            .length > 0 ? 'pointer' : 'default';
                                    },
                                    animation: {
                                        duration: 1000,
                                        easing: 'easeInOutQuart'
                                    }
                                }
                            });
                        } else {
                            console.error(
                                'Chart.js tidak tersedia atau canvas tidak ditemukan untuk prediksi {{ $prediksi->id }}'
                            );
                        }
                    });

                    // Cleanup when modal closes
                    modal{{ $prediksi->id }}.addEventListener('hidden.bs.modal', function() {
                        if (window.prediksiChartInstance{{ $prediksi->id }}) {
                            window.prediksiChartInstance{{ $prediksi->id }}.destroy();
                            window.prediksiChartInstance{{ $prediksi->id }} = null;
                        }
                    });
                }
            @endif
        });
    </script>
@endpush

<style>
    @media print {
        .modal-footer {
            display: none !important;
        }
    }
</style>
