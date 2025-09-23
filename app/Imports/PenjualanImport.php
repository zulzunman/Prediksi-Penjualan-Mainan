<?php

namespace App\Imports;

use App\Models\Penjualan;
use App\Models\Barang;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Carbon\Carbon;

class PenjualanImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    use Importable;

    public function model(array $row)
    {
        // Cari barang berdasarkan nama
        $barang = Barang::where('nama_barang', $row['nama_barang'])->first();

        if (!$barang) {
            throw new \Exception("Barang '{$row['nama_barang']}' tidak ditemukan dalam database.");
        }

        // Validasi stok
        if ($barang->stok < $row['jumlah_penjualan']) {
            throw new \Exception("Stok barang '{$row['nama_barang']}' tidak mencukupi. Stok tersedia: {$barang->stok}, diminta: {$row['jumlah_penjualan']}");
        }

        // Parse tanggal
        $tanggal = $this->parseDate($row['tanggal']);

        // Kurangi stok barang
        $barang->decrement('stok', $row['jumlah_penjualan']);

        return new Penjualan([
            'nama_barang' => $row['nama_barang'],
            'jumlah_penjualan' => $row['jumlah_penjualan'],
            'harga_satuan' => $barang->harga,
            'stok' => $barang->stok, // Stok setelah dikurangi
            'tanggal' => $tanggal,
            'total_harga' => $row['jumlah_penjualan'] * $barang->harga,
        ]);
    }

    private function parseDate($date)
    {
        if (is_numeric($date)) {
            // Excel serial date number
            return Carbon::createFromFormat('Y-m-d', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date)->format('Y-m-d'));
        }

        // Try various date formats
        $formats = ['Y-m-d', 'd/m/Y', 'd-m-Y', 'Y/m/d'];

        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $date);
            } catch (\Exception $e) {
                continue;
            }
        }

        // If all fails, try Carbon's flexible parsing
        try {
            return Carbon::parse($date);
        } catch (\Exception $e) {
            throw new \Exception("Format tanggal '{$date}' tidak valid. Gunakan format YYYY-MM-DD atau DD/MM/YYYY");
        }
    }

    public function rules(): array
    {
        return [
            'nama_barang' => 'required|string|max:255',
            'jumlah_penjualan' => 'required|integer|min:1',
            'tanggal' => 'required',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama_barang.required' => 'Nama barang wajib diisi',
            'jumlah_penjualan.required' => 'Jumlah penjualan wajib diisi',
            'jumlah_penjualan.integer' => 'Jumlah penjualan harus berupa angka',
            'jumlah_penjualan.min' => 'Jumlah penjualan minimal 1',
            'tanggal.required' => 'Tanggal wajib diisi',
        ];
    }
}
