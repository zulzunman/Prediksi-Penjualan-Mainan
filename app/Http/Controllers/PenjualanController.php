<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PenjualanImport;
use App\Exports\PenjualanTemplateExport;

class PenjualanController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $bulan = $request->get('bulan', 'all');
        $tahun = $request->get('tahun', null);
        $barang_filter = $request->get('barang_filter', '');
        $activeTab = $request->get('tab', 'data');

        // Pagination settings
        $perPage = $request->get('per_page', 10);
        $perPageOptions = [10, 15, 25, 50, 100];

        // Get all sales data with filters and pagination
        $penjualanQuery = Penjualan::query();

        // Apply year filter
        if ($tahun) {
            $penjualanQuery->whereYear('tanggal', $tahun);
        }

        // Apply month filter (allow "all months" option)
        if ($bulan !== 'all') {
            $penjualanQuery->whereMonth('tanggal', $bulan);
        }

        // Paginate the data penjualan
        $penjualan = $penjualanQuery->orderBy('tanggal', 'desc')
            ->paginate($perPage, ['*'], 'page')
            ->appends($request->query());

        // Get summary data (ringkasan) with filters and pagination
        $ringkasanQuery = Penjualan::select('nama_barang')
            ->selectRaw('SUM(jumlah_penjualan) as total_terjual')
            ->selectRaw('SUM(total_harga) as total_pendapatan')
            ->selectRaw('AVG(harga_satuan) as harga_rata_rata')
            ->selectRaw('COUNT(*) as jumlah_transaksi')
            ->selectRaw('MAX(tanggal) as transaksi_terakhir');

        // Apply year filter for summary
        if ($tahun) {
            $ringkasanQuery->whereYear('tanggal', $tahun);
        }

        // Apply month filter for summary (allow "all months" option)
        if ($bulan && $bulan !== 'all') {
            $ringkasanQuery->whereMonth('tanggal', $bulan);
        }

        // Apply barang filter for summary
        if ($barang_filter) {
            $ringkasanQuery->where('nama_barang', $barang_filter);
        }

        // Paginate the ringkasan data
        $ringkasanPenjualan = $ringkasanQuery->groupBy('nama_barang')
            ->orderBy('total_pendapatan', 'desc')
            ->paginate($perPage, ['*'], 'ringkasan_page')
            ->appends($request->query());

        // NEW: Get monthly breakdown for specific item when "all months" is selected
        $monthlyBreakdown = [];
        if ($activeTab === 'ringkasan' && $barang_filter && $bulan === 'all' && $tahun) {
            $monthlyData = Penjualan::select(
                DB::raw('MONTH(tanggal) as bulan'),
                DB::raw('SUM(jumlah_penjualan) as total_terjual'),
                DB::raw('SUM(total_harga) as total_pendapatan'),
                DB::raw('AVG(harga_satuan) as harga_rata_rata'),
                DB::raw('COUNT(*) as jumlah_transaksi')
            )
            ->where('nama_barang', $barang_filter)
            ->whereYear('tanggal', $tahun)
            ->groupBy(DB::raw('MONTH(tanggal)'))
            ->orderBy('bulan')
            ->get();

            foreach ($monthlyData as $data) {
                $monthlyBreakdown[$data->bulan] = $data;
            }
        }

        // Get available stock items for create form
        $barang = Barang::stokTersedia()->get();

        // Get all available barang names for filter dropdown (from penjualan table)
        $barangFilter = Penjualan::select('nama_barang')
            ->distinct()
            ->orderBy('nama_barang')
            ->pluck('nama_barang');

        // Get months and years for filter dropdown based on actual data
        $availableMonths = Penjualan::selectRaw('DISTINCT MONTH(tanggal) as month, YEAR(tanggal) as year')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        $availableYears = Penjualan::selectRaw('DISTINCT YEAR(tanggal) as year')
            ->orderBy('year', 'desc')
            ->get();

        // Calculate totals for current period (without pagination)
        $totalQuery = Penjualan::query();
        if ($tahun) {
            $totalQuery->whereYear('tanggal', $tahun);
        }
        if ($bulan && $bulan !== 'all') {
            $totalQuery->whereMonth('tanggal', $bulan);
        }

        $allPenjualan = $totalQuery->get();
        $totalPendapatan = $allPenjualan->sum('total_harga');
        $totalTransaksi = $allPenjualan->count();
        $totalBarangTerjual = $allPenjualan->sum('jumlah_penjualan');

        // Month names for display
        $monthNames = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        return view('penjualan.index', compact(
            'penjualan',
            'barang',
            'ringkasanPenjualan',
            'bulan',
            'tahun',
            'barang_filter',
            'activeTab',
            'availableMonths',
            'availableYears',
            'barangFilter',
            'totalPendapatan',
            'totalTransaksi',
            'totalBarangTerjual',
            'monthNames',
            'perPage',
            'perPageOptions',
            'monthlyBreakdown' // NEW: Pass monthly breakdown data
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'jumlah_penjualan' => 'required|integer|min:1',
            'tanggal' => 'required|date',
        ]);

        // Cari barang berdasarkan ID
        $barang = Barang::findOrFail($request->barang_id);

        // Validasi stok mencukupi
        if ($barang->stok < $request->jumlah_penjualan) {
            return redirect()->back()->withInput()->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . $barang->stok . ' unit');
        }

        // Kurangi stok barang
        $barang->decrement('stok', $request->jumlah_penjualan);

        // Buat data penjualan
        $data = [
            'nama_barang' => $barang->nama_barang,
            'jumlah_penjualan' => $request->jumlah_penjualan,
            'harga_satuan' => $barang->harga,
            'stok' => $barang->stok,
            'tanggal' => $request->tanggal,
            'total_harga' => $request->jumlah_penjualan * $barang->harga,
        ];

        try {
            Penjualan::create($data);

            // Redirect with current tab and filter parameters
            $redirectUrl = route('penjualan.index', [
                'bulan' => $request->get('bulan', date('m')),
                'tahun' => $request->get('tahun', date('Y')),
                'barang_filter' => $request->get('barang_filter', ''),
                'tab' => $request->get('tab', 'data'),
                'per_page' => $request->get('per_page', 15)
            ]);

            return redirect($redirectUrl)->with('success', 'Data penjualan berhasil ditambahkan. Stok barang telah diperbarui.');
        } catch (\Exception $e) {
            // Kembalikan stok jika terjadi error
            $barang->increment('stok', $request->jumlah_penjualan);
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function getBarang($id)
    {
        $barang = Barang::find($id);
        if ($barang) {
            return response()->json([
                'success' => true,
                'data' => [
                    'nama_barang' => $barang->nama_barang,
                    'harga' => $barang->harga,
                    'stok' => $barang->stok,
                    'formatted_harga' => number_format($barang->harga, 0, ',', '.')
                ]
            ]);
        }
        return response()->json(['success' => false]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'jumlah_penjualan' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'tanggal' => 'required|date',
        ]);

        $data = [
            'nama_barang' => $request->nama_barang,
            'jumlah_penjualan' => $request->jumlah_penjualan,
            'harga_satuan' => $request->harga_satuan,
            'stok' => $request->stok,
            'tanggal' => $request->tanggal,
            'total_harga' => $request->jumlah_penjualan * $request->harga_satuan,
        ];

        try {
            $penjualan = Penjualan::findOrFail($id);
            $penjualan->update($data);

            // Redirect with current tab and filter parameters
            $redirectUrl = route('penjualan.index', [
                'bulan' => $request->get('bulan', date('m')),
                'tahun' => $request->get('tahun', date('Y')),
                'barang_filter' => $request->get('barang_filter', ''),
                'tab' => $request->get('tab', 'data'),
                'per_page' => $request->get('per_page', 15),
                'page' => $request->get('page', 1)
            ]);

            return redirect($redirectUrl)->with('success', 'Data penjualan berhasil diupdate.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate data: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $penjualan = Penjualan::findOrFail($id);
            $penjualan->delete();
            return redirect()->route('penjualan.index')->with('success', 'Data penjualan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        try {
            $fileName = 'template_penjualan_' . date('Y-m-d') . '.xlsx';
            return Excel::download(new PenjualanTemplateExport(), $fileName);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengunduh template: ' . $e->getMessage());
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            DB::beginTransaction();

            $import = new PenjualanImport();
            Excel::import($import, $request->file('excel_file'));

            DB::commit();

            // Get current filter parameters to maintain state
            $redirectParams = [
                'bulan' => $request->get('bulan', date('m')),
                'tahun' => $request->get('tahun', date('Y')),
                'barang_filter' => $request->get('barang_filter', ''),
                'tab' => $request->get('tab', 'data'),
                'per_page' => $request->get('per_page', 15)
            ];

            return redirect()->route('penjualan.index', $redirectParams)
                ->with('success', 'Data penjualan berhasil diimpor dari Excel.');

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            DB::rollBack();

            $failures = $e->failures();
            $errorMessages = [];

            foreach ($failures as $failure) {
                $errorMessages[] = "Baris " . $failure->row() . ": " . implode(', ', $failure->errors());
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Validasi gagal: ' . implode(' | ', $errorMessages));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }
}
