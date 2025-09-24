<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\Prediksi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PrediksiController extends Controller
{
    public function index()
    {
        $prediksi = Prediksi::with('barang')->latest()->get();

        // Ambil data untuk modal create
        $barang = Barang::join('penjualan', 'barang.nama_barang', '=', 'penjualan.nama_barang')
            ->select('barang.*')
            ->distinct()
            ->get();

        return view('prediksi.index', compact('prediksi', 'barang'));
    }

    public function show($id)
    {
        $prediksi = Prediksi::with('barang')->findOrFail($id);
        return view('prediksi.show', compact('prediksi'));
    }

    public function create()
    {
        // Ambil hanya barang yang pernah ada di penjualan
        $barang = Barang::join('penjualan', 'barang.nama_barang', '=', 'penjualan.nama_barang')
            ->select('barang.*')
            ->distinct()
            ->get();

        return view('prediksi.create', compact('barang'));
    }

    // Method baru untuk mendapatkan data tahun dan bulan berdasarkan barang
    public function getAvailableData(Request $request)
    {
        if (!$request->barang_id) {
            return response()->json([]);
        }

        $barang = Barang::findOrFail($request->barang_id);

        // Ambil semua data penjualan berdasarkan barang yang dipilih
        $penjualanData = Penjualan::where('nama_barang', $barang->nama_barang)
            ->selectRaw('YEAR(tanggal) as tahun, MONTH(tanggal) as bulan, COUNT(*) as count')
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'asc')
            ->get();

        // Kelompokkan data per tahun
        $groupedData = $penjualanData->groupBy('tahun');

        // Buat struktur data untuk response
        $result = [];
        foreach ($groupedData as $tahun => $bulanData) {
            $months = [];
            $availableMonths = $bulanData->pluck('bulan')->toArray();

            // Array bulan 1-12
            for ($i = 1; $i <= 12; $i++) {
                $months[] = [
                    'number' => $i,
                    'name' => $this->getMonthName($i),
                    'available' => in_array($i, $availableMonths)
                ];
            }

            $result[] = [
                'year' => $tahun,
                'months' => $months
            ];
        }

        return response()->json($result);
    }

    // Method untuk mendapatkan nama bulan dalam bahasa Indonesia
    private function getMonthName($monthNumber)
    {
        $monthNames = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug',
            9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
        ];

        return $monthNames[$monthNumber];
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id'     => 'required|exists:barang,id',
            'tahun_dataset' => 'nullable|integer',
            'bulan_dataset' => 'nullable|integer|min:1|max:12',
        ]);

        // Set periode tetap 3 bulan
        $request->merge(['periode' => 3]);

        $barang = Barang::findOrFail($request->barang_id);

        // Build query untuk data penjualan
        $query = Penjualan::selectRaw('YEAR(tanggal) as tahun, MONTH(tanggal) as bulan, SUM(jumlah_penjualan) as total')
            ->where('nama_barang', $barang->nama_barang);

        // Filter berdasarkan tahun dan bulan yang dipilih
        if ($request->tahun_dataset) {
            $query->whereYear('tanggal', $request->tahun_dataset);

            if ($request->bulan_dataset) {
                // Jika bulan dipilih, ambil data dari bulan tersebut hingga data terakhir yang tersedia
                $query->where(function ($q) use ($request) {
                    $q->where(function ($subQ) use ($request) {
                        $subQ->whereYear('tanggal', $request->tahun_dataset)
                             ->whereMonth('tanggal', '>=', $request->bulan_dataset);
                    })->orWhere(function ($subQ) use ($request) {
                        $subQ->whereYear('tanggal', '>', $request->tahun_dataset);
                    });
                });
            }
        }

        $penjualan = $query->groupBy('tahun', 'bulan')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();

        // Validasi minimal 5 bulan data
        if ($penjualan->count() < 5) {
            return back()->with('error', 'Data penjualan minimal harus 5 bulan untuk prediksi yang akurat.');
        }

        // --- Ambil tanggal terakhir (max) ---
        $lastDateQuery = Penjualan::where('nama_barang', $barang->nama_barang);
        if ($request->tahun_dataset) {
            $lastDateQuery->whereYear('tanggal', $request->tahun_dataset);
            if ($request->bulan_dataset) {
                $lastDateQuery->where(function ($q) use ($request) {
                    $q->where(function ($subQ) use ($request) {
                        $subQ->whereYear('tanggal', $request->tahun_dataset)
                             ->whereMonth('tanggal', '>=', $request->bulan_dataset);
                    })->orWhere(function ($subQ) use ($request) {
                        $subQ->whereYear('tanggal', '>', $request->tahun_dataset);
                    });
                });
            }
        }
        $lastDate = $lastDateQuery->max('tanggal');

        // Ambil total bulanan sebagai array Y
        $y = $penjualan->pluck('total')->toArray();
        $n = count($y);
        $x = range(1, $n);

        // Hitung regresi linear
        $x_sum = array_sum($x);
        $y_sum = array_sum($y);
        $xy_sum = array_sum(array_map(fn ($xi, $yi) => $xi * $yi, $x, $y));
        $x2_sum = array_sum(array_map(fn ($xi) => $xi * $xi, $x));

        $b = ($n * $xy_sum - $x_sum * $y_sum) / ($n * $x2_sum - $x_sum ** 2);
        $a = ($y_sum - $b * $x_sum) / $n;

        // Tentukan bulan terakhir
        if ($lastDate) {
            $lastMonth = Carbon::parse($lastDate)->startOfMonth();
        } else {
            $lastRow = $penjualan->last();
            $lastMonth = Carbon::createFromDate($lastRow->tahun, $lastRow->bulan, 1);
        }

        // Nama bulan Indonesia
        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        // Buat hasil prediksi
        $hasilPrediksi = [];
        for ($i = 1; $i <= $request->periode; $i++) {
            $periodeX = $n + $i;
            $value = round($a + $b * $periodeX);

            $next = $lastMonth->copy()->addMonths($i);

            $hasilPrediksi[] = [
                'tahun' => (int)$next->format('Y'),
                'bulan' => (int)$next->format('n'),
                'label' => $monthNames[(int)$next->format('n')] . ' ' . $next->format('Y'),
                'nilai' => $value,
            ];
        }

        // Urutkan agar Januari, Februari, Maret ...
        $hasilPrediksi = collect($hasilPrediksi)
            ->sortBy(fn ($item) => $item['tahun'] * 100 + $item['bulan'])
            ->values()
            ->all();

        // Hitung MAPE
        $mape = 0;
        foreach ($y as $i => $yi) {
            $y_hat = $a + $b * ($i + 1);
            if ($yi != 0) {
                $mape += abs(($yi - $y_hat) / $yi);
            }
        }
        $mape = round(($mape / $n) * 100, 2);

        // Simpan hasil
        Prediksi::create([
            'barang_id'      => $barang->id,
            'metode'         => 'Regresi Linear',
            'periode'        => $request->periode,
            'hasil_prediksi' => $hasilPrediksi,
            'mape'           => $mape,
        ]);

        return redirect()->route('prediksi.index')->with('success', 'Prediksi berhasil disimpan.');
    }
}
