<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';

    protected $fillable = [
       'nama_barang',
       'jumlah_penjualan',
       'harga_satuan',
       'stok',
       'tanggal',
       'total_harga',
    ];
}
