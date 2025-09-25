@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Hasil Prediksi</h3>
        <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createPrediksiModal">
            + Tambah Prediksi
        </button>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Barang</th>
                    <th>Metode</th>
                    <th>Dataset Info</th>
                    <th>Periode</th>
                    <th>Hasil Prediksi</th>
                    <th>MAPE (%)</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($prediksi as $p)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $p->barang->nama_barang ?? '-' }}</td>
                        <td>{{ $p->metode }}</td>
                        <td>
                            <small class="text-muted">
                                {{ $p->dataset_info ?? 'Data tidak tersedia' }}
                            </small>
                        </td>
                        <td>{{ $p->periode }}</td>
                        <td>
                            @php
                                // Normalisasi hasil_prediksi untuk menangani format lama (assoc 'Label' => value)
                                // dan format baru (array of ['label'=>..., 'nilai'=>...])
                                $raw = $p->hasil_prediksi;
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

                            @foreach ($items as $it)
                                <div class="small">{{ $it['label'] }} : <strong>{{ number_format($it['nilai']) }}</strong>
                                </div>
                            @endforeach
                        </td>
                        <td>
                            <span class="badge bg-{{ $p->mape < 10 ? 'success' : ($p->mape < 20 ? 'warning' : 'danger') }}">
                                {{ $p->mape }}%
                            </span>
                        </td>
                        <td>{{ $p->created_at->format('d-m-Y H:i') }}</td>
                        <td>
                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                data-bs-target="#showPrediksiModal{{ $p->id }}">
                                <i class="fas fa-eye"></i> Detail
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Include Modals --}}
    @include('prediksi.create')

    @foreach ($prediksi as $p)
        @include('prediksi.show', ['prediksi' => $p])
    @endforeach
@endsection
