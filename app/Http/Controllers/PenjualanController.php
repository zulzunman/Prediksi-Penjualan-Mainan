<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Barang;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    public function index()
    {
        $penjualan = Penjualan::all();
        $barang = Barang::stokTersedia()->get(); // Tambahkan ini untuk mengambil barang yang stoknya > 0
        return view('penjualan.index', compact('penjualan', 'barang'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id', // Ubah dari nama_barang ke barang_id
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
            'stok' => $barang->stok, // Stok setelah dikurangi
            'tanggal' => $request->tanggal,
            'total_harga' => $request->jumlah_penjualan * $barang->harga,
        ];

        try {
            Penjualan::create($data);
            return redirect()->route('penjualan.index')->with('success', 'Data penjualan berhasil ditambahkan. Stok barang telah diperbarui.');
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
            return redirect()->route('penjualan.index')->with('success', 'Data penjualan berhasil diupdate.');
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
}
