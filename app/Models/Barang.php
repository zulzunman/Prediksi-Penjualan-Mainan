<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';

    protected $fillable = [
        'nama_barang',
        'stok',
        'harga'
    ];

    protected $casts = [
        'stok' => 'integer',
        'harga' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Accessor untuk format harga
    public function getFormattedHargaAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    // Accessor untuk total nilai (harga x stok)
    public function getTotalNilaiAttribute()
    {
        return $this->harga * $this->stok;
    }

    // Accessor untuk format total nilai
    public function getFormattedTotalNilaiAttribute()
    {
        return 'Rp ' . number_format($this->total_nilai, 0, ',', '.');
    }

    // Accessor untuk status stok
    public function getStatusStokAttribute()
    {
        if ($this->stok > 10) {
            return 'tersedia';
        } elseif ($this->stok > 0) {
            return 'menipis';
        } else {
            return 'habis';
        }
    }

    // Scope untuk filter berdasarkan status stok
    public function scopeStokTersedia($query)
    {
        return $query->where('stok', '>', 0);
    }

    public function scopeStokHabis($query)
    {
        return $query->where('stok', '=', 0);
    }

    public function scopeStokMenipis($query)
    {
        return $query->where('stok', '>', 0)->where('stok', '<', 10);
    }

    // Scope untuk pencarian
    public function scopeSearch($query, $search)
    {
        return $query->where('nama_barang', 'like', '%' . $search . '%');
    }
}
